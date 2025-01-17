<div class="titulo-linea mt-2">
  <h2><i class="flaticon-022-paw"></i>Lista Raza</h2>
  <hr class="sidebar-divider">
</div>
<div class="intro mb-4">
  <div class="row">
    <div class="col-12 mb-4">
      <div class="row">
        <?php 
            $busqueda="";
          
            if(isset($_SESSION['busqueda_raza'])==false){
              $busqueda="";
              $btn_mostrar_todos="";
            }else{
              $busqueda=$_SESSION['busqueda_raza'];
              $btn_mostrar_todos='<button type="submit" class="btn btn-secondary ml-2 btn-circle btn-xs" data-toggle="tooltip" title="Mostrar todos"><i class="fas fa-paw"></i></button>';
            }
            
         ?>
        <div class="col-md-6 mb-2">
          <form class="Formulario_ajax_simple" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" autocomplete="off">
            <div class="d-flex flex-row">
              <input type="hidden" name="modulo" value="raza">
              <div class="input-group group mb-0 mt-0">
                <input type="text" class="p-0" name="busqueda_inicial" id="inputSearch" placeholder="Buscar raza por nombre"/>
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
                <input type="hidden" name="modulo" value="raza">
                <?php echo $btn_mostrar_todos; ?>
              </form>
            </div>
            <div class="col-md-6">
              <button data-toggle="modal" data-target="#exampleModalRazaNueva" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus mr-2"></i>Nueva Raza</button>    
            </div>
          </div>
          
        </div>
      </div>
          
    </div>
    <div class="col-12"> 
      
      <!-- tabla y paginador -->
        <?php
          require_once "./controladores/razaControlador.php";
          $ins_raza= new razaControlador();
          // pagina: views
          echo $ins_raza->paginador_raza_controlador($pagina[1],10,$_SESSION['privilegio_vetp'],$pagina[0],$busqueda); 
         ?>  

    </div>
  </div>
 
  <!-- MODAL-REGISTRO NUEVA RAZA -->
  <div class="modal fade" id="exampleModalRazaNueva" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-paw fa-fw fa-sm mr-2"></i>Nueva Raza</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <form action="<?php  echo SERVERURL; ?>ajax/razaAjax.php" data-form="save"  method="POST" class="FormularioAjax">
            <div class="modal-body">
              <div class="group">
                <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" name="raza_nombre_reg" id="raza_nombre" />
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Raza</label>
              </div>
              <div class="group">
                <select name="raza_especie_reg" id="raza_especie" required>
                    <?php  
                        require_once "./controladores/especieControlador.php";
                        $insEm=new especieControlador();

                        $dataE=$insEm->buscar_especie_controlador("Select");
       
                        while($rowE=$dataE->fetch()){
                          echo '<option value="'.$ins_loginc->encryption($rowE['idEspecie']).'">'.$rowE['espNombre'].'</option>';
                        }
                      ?>
                </select>
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
  <!-- modal -->
  <!-- MODAL- EDITAR RAZA -->
  <div class="modal fade" id="exampleModalRazaEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-paw fa-fw fa-sm"></i> Editar Raza</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <form class="FormularioAjax" action="<?php  echo SERVERURL; ?>ajax/razaAjax.php" data-form="update"  method="POST">
            <div class="modal-body">
              <input type="hidden" name="raza_id_edit" id="id_raza_edit">
              <div class="group">
                <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" name="raza_nombre_edit" id="id_raza_nombre" required=""/>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Raza</label>
              </div>
              <div class="group">
                <select name="raza_especie_edit" id="id_raza_especie" required>
                  <?php  
                    require_once "./controladores/especieControlador.php";
                    $insEm=new especieControlador();

                    $dataE=$insEm->buscar_especie_controlador("Select");
   
                    while($rowE=$dataE->fetch()){
                      echo '<option value="'.$rowE['idEspecie'].'">'.$rowE['espNombre'].'</option>';
                    }

                  ?>
                </select>
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
