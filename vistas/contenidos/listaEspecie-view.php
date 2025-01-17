<div class="titulo-linea mt-2">
  <h2><i class="flaticon-022-paw"></i>Lista Especies</h2>
  <hr class="sidebar-divider">
</div>            
<div class="intro lista-especie mb-4">
  <div class="row">
    <div class="col-12 mb-4">
      <div class="row">
        <?php 
            $busqueda="";
          
            if(isset($_SESSION['busqueda_especie'])==false){
              $busqueda="";
              $btn_mostrar_todos="";
            }else{
              $busqueda=$_SESSION['busqueda_especie'];
              $btn_mostrar_todos='<button type="submit" class="btn btn-secondary ml-2 btn-circle btn-xs" data-toggle="tooltip" title="Mostrar todos"><i class="fas fa-paw"></i></button>';
            }
            
         ?>
        <div class="col-md-6 mb-2">
          <form class="Formulario_ajax_simple" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" autocomplete="off">
            <div class="d-flex flex-row">
              <input type="hidden" name="modulo" value="especie">
              <div class="input-group group mb-0 mt-0">
                <input type="text" class="p-0" name="busqueda_inicial" id="inputSearch" placeholder="Buscar especie por nombre"/>
                  <div class="input-group-addon input-group-append">
                      <div class="input-icon">
                          <i class="fa fa-search"></i>
                      </div>
                  </div>
              </div>
              <div>
                <button type="submit" class="btn btn-primary ml-2 btn-circle btn-xs"><i class="fas fa-search"></i></button>
              </div>  
            </div>
          </form>
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-6">
              <form class="col-6 Formulario_ajax_simple" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" autocomplete="off">
                <input type="hidden" name="mostrar_todos" value="">
                <input type="hidden" name="modulo" value="especie">
                <?php echo $btn_mostrar_todos; ?>
              </form>
            </div>
            <div class="col-md-6">
              <button data-toggle="modal" data-target="#exampleModalEspecieNueva" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus mr-2"></i>Nueva Especie</button>    
            </div>
          </div>
          
        </div>
      </div>
      <!-- <input type="search" name="" placeholder="Buscar"> -->
    </div>
    <div class="col-12">  

      <!-- tabla y paginador -->
        <?php
          require_once "./controladores/especieControlador.php";
          $ins_especie= new especieControlador();
          // pagina: views
          echo $ins_especie->paginador_especie_controlador($pagina[1],5,$_SESSION['privilegio_vetp'],$pagina[0],$busqueda); 
         ?>       
            
    </div>
  </div>
  <!-- MODAL-REGISTRO NUEVA ESPECIE -->
  <div class="modal fade" id="exampleModalEspecieNueva" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-paw fa-fw fa-sm"></i> Nueva Especie</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <form action="<?php  echo SERVERURL; ?>ajax/especieAjax.php" data-form="save"  method="POST" class="FormularioAjax">
            <div class="modal-body">
              <div class="group">
                <input type="text" name="especie_nombre_reg" id="especie_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" required="" />
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Especie</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
      </div>
    </div>
  </div>
  <!-- modal nueva especie-->
  <!-- MODAL- EDITAR ESPECIE -->
  <div class="modal fade" id="exampleModalEspecieEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-paw fa-fw fa-sm"></i> Editar Especie</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <form class="FormularioAjax" action="<?php  echo SERVERURL; ?>ajax/especieAjax.php" data-form="update"  method="POST">
            <div class="modal-body">
              <input type="hidden" name="especie_id_edit" id="id_especie_id" >
              <div class="group">
                <input type="text" name="especie_nombre_edit" id="id_especie_nombre" required=""/>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Especie</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
      </div>
    </div>
  </div>
  <!-- modal EDITAR x especie-->
</div>