<div class="titulo-linea mt-2">
  <h2><i class="far fa-user"></i>Perfil Cliente</h2>
  <hr class="sidebar-divider">
</div>
<div class="row perfil-cliente">
  <?php
    require_once "./controladores/clienteControlador.php";
    $inst_cliente = new clienteControlador();

    $datos_cliente=$inst_cliente->datos_cliente_controlador("Unico",$pagina[1]);

    if($datos_cliente->rowCount()==1){
      $campos=$datos_cliente->fetch();
    
   ?>
  <!-- ------- DATOS CLIENTE GENERAL------ -->
  <div class="col-lg-8 mb-4">
    <div class="intro h-100"> 
      <div class="row">
        <!-- foto perfil -->
        <div class="col-lg-4 mb-4">
          <!-- profile-img-cli -->
          <div class="profile-img-cli text-center mb-4">
            <img class="rounded-circle" src="<?php  echo SERVERURL;?><?php echo $campos['clienteFotoUrl'];?>" alt="foto-cliente">
            <h5 class="mt-2"><b><?php echo $campos['clienteNombre']." ".$campos['clienteApellido'] ; ?></b></h5>
          </div>
          <!-- X profile-img-cli x -->
          <!-- inf. contacto -->
          <input type="hidden" name="dni_cliente_perfil" value="<?php echo $campos['clienteDniCedula'];?>">
          <div class="inf-contacto d-flex flex-column">
            <span><i class="fas fa-phone fa-sm fa-fw"></i><?php echo $campos['clienteTelefono'];?></span>
            <br>
            <span><i class="fas fa-envelope fa-sm fa-fw"></i><?php echo $campos['clienteCorreo'];?></span>  
          </div>
          <!-- X inf. contacto X -->
        </div>
        <!--X foto perfil X-->
        <!-- info general -->
        <div class="col-lg-8">
          <div class="inf-profile">     
          <h3 class="sub-titulo-panel">
              <i class="far fa-id-card"></i>
            Información General</h3>
            <hr>
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 mb-4">
                <span>NIT/CI: <br><b><?php echo $campos['clienteDniCedula'];?></b></span>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4">
                <span>Nombre: <br><b><?php echo $campos['clienteNombre'];?></b></span>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4">
                <span>Apellido: <br><b><?php echo $campos['clienteApellido'];?></b></span>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4">
                <span>Genero: <br><b><?php echo $campos['clienteGenero'];?></b></span>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4">
                  <span>Domicilio: <br><b><?php echo $campos['clienteDomicilio'];?></b></span>
              </div>                       
            </div>
                
          </div>
        </div>
        <!-- x info general x -->
      </div>
    </div>
  </div>
  <!-- --X X DATOS CLIENTE GENERAL X X------ -->

  <!-- ---------- LISTA MASCOTAS -------- -->
  <div class="col-lg-4 mascota-inf mb-4">
    <div class="intro">
      <!-- header -->
      <h3 class="sub-titulo-panel">
          <i class="fas fa-paw"></i>
        Mascotas</h3>
        <a href="<?php echo SERVERURL."addMascota/".$pagina[1]; ?>" style="cursor: pointer;" class="btn btn-sm btn-primary float-right">
          <i class="fas fa-plus mr-2"></i>Nueva</a>
        <hr>
        <!-- header -->
      <!-- CARGAR LISTA -->
      <div class="lista-mascotas overflow-auto scroll-style-1">
        <ul class="list-group">
          <!-- ***dinamico***(cargar lista mascota) -->
          <?php
          require_once "./controladores/clienteControlador.php";
          $inst_cliente = new clienteControlador();

            echo $inst_cliente->datos_perfil_cliente_controlador("listaMascota",$pagina[1]);
            
           ?>
          <!-- **x*dinamico**x* -->
        </ul>
        
        <!-- -------MODAL-LISTA-MASCOTAS-DETALLE- ------- -->
        <div class="modal fade" id="modalDetalleMascota" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  <i class="fas fa-paw"></i>
                Detalle Mascota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <!-- ***dinamico*** -->
                  <div class="col-lg-4 d-flex flex-column text-center modal_img">
                    <img class="img" src="<?php  echo SERVERURL;?>vistas/images/general/image-preve.svg">
                    <h5><b id="modal_nombre">Lucky</b></h5>
                    <!-- <div ></div> -->
                    <span>Especie: <b id="modal_especie">Canino</b></span>
                    <span>Raza:  <b id="modal_raza">Poodle</b></span>
                    <span>Codigo:  <b id="modal_codigo">CM-8765-1</b></span>
                  </div>
                  <div class="col-lg-8">
                    <div class="row">
                      <div class="col-12 mb-4 d-flex justify-content-between">
                        <h6>
                        <i class="fas fa-cat mr-2"></i>
                      Información General</h6>
                        <a  href="<?php  echo SERVERURL."perfilMascota/".$ins_loginc->encryption("no")."/";?>" id="modal_perfil" style="color: #00ff00;"><i class="fa fa-eye fa-fw fa-sm mr-2"></i>Ver Perfil</a>                                  
                      </div>
                      

                      <div class="col-6 mb-2">
                        <span>Fecha N. : <br><b id="modal_fecha">05-12-2020</b></span>
                      </div>
                      <div class="col-6">
                        <span>Peso : <br><b id="modal_peso">20lib</b></span>
                      </div>
                      <div class="col-6 mb-2">
                          <span>Sexo : <br><b id="modal_sexo">Macho</b></span>
                      </div>
                      
                      <div class="col-6">
                        <span>Color : <br><b id="modal_color">Marron</b></span>
                      </div>
                      
                      <div class="col-12">
                        <span>Inf. Adicional : <br><b id="modal_adicioanl">Lorem ipsum</b></span>
                      </div>
                      
                    </div>
                  </div>
                  <!-- x***dinamico***x -->
                </div>
              </div>
              <!--  -->
            </div>
          </div>
        </div>
        <!-- ---X-- ----MODAL-LISTA-MASCOTAS-DETALLE- ----X---- -->
      </div>
      <!-- x CARGAR LISTA x -->
    </div>
  </div>
  <!-- ---X--X---- LISTA MASCOTAS ---X--X-- -->
  <!-- ----------- HISTORIAL FACTURADO --------  -->
  <div class="col-lg-12">
    <div class="panel_historial_factura intro mb-4" urlajax="<?php  echo SERVERURL;?>ajax/clienteAjax.php" >
      <!-- header -->
      <div class="d-flex flex-row justify-content-between">
        <div class="d-flex flex-row">
          <div>
          <h3 class="sub-titulo-panel">
            <i class="far fa-credit-card"></i>
              Historial Facturado
          </h3>  
          </div>
          
          <div>
          <span class="badge badge-info badge-top total-factura">0</span>
            
          </div>
        </div>
         
        <!-- --BUSCAR POR FECHA -->
        <div class="d-flex flex-row">
          <div class="input-group date group mt-0 mb-0" id="fecha_perfil_cli">
            <input type="text" name="fecha_buscar_perfilc" placeholder="Buscar por fecha"/>
              <div class="input-group-addon input-group-append">
                  <div class="input-icon">
                      <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                  </div>
              </div>
          </div>
            
          <button class="btn btn-primary ml-2 btn-circle btn-xs" id="btn_buscarfecha">
            <i class="fas fa-search"></i>
          </button>  
        </div>
        <!-- -X- BUSCAR POR FECHA -x- -->
      </div>
      <hr>  
      <!-- X header X -->
        <div style="max-height: 500px;" class="overflow-auto scroll-style-1">
          <ul class="historial-list" id="results_factura">
            <!-- ***dinamico***(cargar lista facturado,5,ver-mas) -->
            
            <!-- *x**dinamico**x* -->
          </ul>
          <div class="text-center">
            <!-- <button class="btn btn-style-2">Ver mas</button> -->
            <button class="btn btn-style-2 btn-sm" id="btn_masfactura">Mostrar Mas</button>
            <div id="loader_mesg"></div>
          </div>
        </div>
    </div>
    <!-- --------MODAL- DETALLE FACTURA --------- -->
    <div class="modal fade" id="modalDetalleFactura" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detalle Factura</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body overflow-auto scroll-style-1" style="max-height: 400px;">
            <div class="table-responsive table-sm">
              <table class="table table-hover mb-0">
                  <thead>
                      <tr class="align-self-center">
                          <th>#</th>
                          <th>Codigo</th>
                          <th>Descripción</th>
                          <th>Precio</th>
                          <th>Cantidad</th>
                          <th>Total</th>
                      </tr>
                  </thead>
                  <tbody id="lista_detalle_venta">
                      <!-- ***dinamico*** -->
                       
                      <!-- x***dinamico***x -->
                  </tbody>
              </table>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- ----X--- MODAL- DETALLE FACTURA ----X--- -->
  </div>
  <!-- ----X------- HISTORIAL FACTURADO ----X----  -->
  <?php }else{ ?>
  <div class="alert alert-danger text-center" role="alert">
    <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
    <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
    <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
  </div>
<?php } ?>
</div> 