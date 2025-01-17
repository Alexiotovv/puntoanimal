 <div class="titulo-linea mt-2">
  <h2><i class="fas fa-box-open"></i>Lista Productos/Servicios</h2>
  <hr class="sidebar-divider">
</div>
<div class="intro mb-4">
  <div class="row">
    <div class="col-12 mb-4">
      <div class="row">
        <?php 
            $busqueda="";
          
            if(isset($_SESSION['busqueda_inventario'])==false){
              $busqueda="";
              $btn_mostrar_todos="";
            }else{
              $busqueda=$_SESSION['busqueda_inventario'];
              $btn_mostrar_todos='<button type="submit" class="btn btn-secondary ml-2 btn-circle btn-xs" data-toggle="tooltip" title="Mostrar todos"><i class="fas fa-box-open"></i></button>';
            }
            
         ?>
        <div class="col-md-6 mb-2">
          <form class="Formulario_ajax_simple" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" autocomplete="off">
            <div class="d-flex flex-row">
              <input type="hidden" name="modulo" value="inventario">
              <div class="input-group group mb-0 mt-0">
                <input type="text" class="p-0" name="busqueda_inicial" id="inputSearch" placeholder="Buscar por nombre o tipo"/>
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
            <div class="col-md-4">
              <form class="col-6 Formulario_ajax_simple" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" autocomplete="off">
                <input type="hidden" name="mostrar_todos" value="">
                <input type="hidden" name="modulo" value="inventario">
                <?php echo $btn_mostrar_todos; ?>
              </form>
            </div>
            <div class="col-md-8">
              <div class="float-right">
                  <!-- IMPRIMIR EN PDF LISTA  -->
               
                <a href="<?php  echo SERVERURL;?>addProdservi/" class="btn btn-primary btn-sm mb-2"><i class="fas fa-plus mr-2"></i>Nuevo Producto/Servicio</a>  
              </div>
            </div>
          </div>
              
        </div>
      </div>
      
    </div>
    <div class="col-12">  
      <!-- tabla y paginador -->
        <?php
          require_once "./controladores/inventarioControlador.php";
          $ins_inventario= new inventarioControlador();
          // pagina: views
          echo $ins_inventario->paginador_inventario_controlador($pagina[1],10,$_SESSION['privilegio_vetp'],$pagina[0],$busqueda); 
         ?> 
    </div>
    <!-- MODAL AGREGAR STOCK producto -->
      <div class="modal fade ModalStockup" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h4 class="modal-title w-100"><i class="fas fa-box mr-2 fa-xs"></i>Agregar stock</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            
            <form action="<?php echo SERVERURL; ?>ajax/inventarioAjax.php" class="FormularioAjax" data-form="update" method="POST" >
              <div class="modal-body">
                <div class="md-form">
                      <input type="hidden" name="cod_producto_stockup" id="cod_prod_up" >
                      <div class="row">
                        <div class="col-12">
                          <small>Producto:</small>
                          <h5 class="text-info" id="txt_prod_up">Desparacitante 300 ml despator</h2>
                        </div>
                        <div class="col-12">    
                          <label>Agregar Stock</label>
                          <input type="number" class="input-sniperr" name="prodservi_stock_up" value="0" min="1" id="inventario-stock" >
                        </div>
                        
                      </div>
                </div>
              </div>
              <div class="modal-footer d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Guardar<i class="fas fa-edit ml-1"></i></button>
              </div>
            </form>

          </div>
        </div>
      </div>
      <!-- X X modal agregar stock X X  -->
  </div> <!-- row  -->

</div>