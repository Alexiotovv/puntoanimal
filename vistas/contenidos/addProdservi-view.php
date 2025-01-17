<div class="titulo-linea mt-2">
  <h2><i class="flaticon-015-anti-bug"></i>Productos y Servicios</h2>
  <hr class="sidebar-divider">
</div>
<?php 
  require_once "./controladores/empresaControlador.php";
  $iEm=new empresaControlador();
  //conteo de empresa
  $cEm=$iEm->datos_empresa_controlador("Unico");
  if($cEm->rowCount()==1){
    $campos=$cEm->fetch();
 ?>
<div class="intro mb-4">
  <form action="<?php  echo SERVERURL; ?>ajax/inventarioAjax.php" data-form="save"  method="POST" class="FormularioAjax">
    <div class="row">
      <!-- INF GENERAL -->
      <div class="col-lg-12">
        <h3 class="sub-titulo-panel">
          <i class="fas fa-plus mr-2"></i>
            Nuevo Inventario</h3>
        <div class="row mt-2">
          <div class="col-md-6">
            <div class="group">
              <input type="text" name="prodservi_nombre_reg" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre</label>
            </div>
          </div> 
          <div class="col-md-6">
            <div class="group">
              <select class="" name="prodservi_tipo_reg" id="prodservi_tipo">
                <option value="Producto">Producto</option>
                <option value="Servicio">Servicio</option>
              </select>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Tipo</label>
            </div>
          </div>
          <div class="col-md-6 d-flex flex-row">
            <div class="group w-75">
              <input type="text"  name="prodservi_precio_reg" id="prodservi_precio" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="$ 0.00" maxlength="15">
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Precio de venta con iva</label>
            </div>
            <samp class="mt-5 ml-2 text-lg"><?php echo $campos['empresaMoneda']; ?></samp>
          </div>
        <!--   <div class="col-md-6">
            <div class="group">
              <input type="text" name="prodservi_vencimiento_reg" id="inventario_vencimiento" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>F. Vencimiento</label>
            </div>
          </div>  -->

          <div class="col-md-6">
                  <div class="input-group date group fechas" >
                    <input type="text" name="prodservi_vencimiento_reg" id="inventario_vencimiento" required=""/>
                    <span class="highlight"></span>
                    <span class="bar"></span>
                    <label>F. Vencimiento</label>
                    <div class="input-group-addon input-group-append">
                      <div class="input-icon">
                          <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
          </div>

          <div class="col-md-6">
            <div class="">
              <label>Stock </label>
              <input type="number" class="input-sniperr" name="prodservi_stock_reg" value="0" min="0" id="inventario-stock" >
            </div>
          </div>
        </div>
      </div>
     
      <div class="col-lg-12 text-center mt-5">
        <button type="submit" class="btn btn-primary">Guardar</button>     
        <a  href="<?php  echo SERVERURL;?>listaProdservi/" class="btn btn-primary">Volver</a>                
      </div>

    </div> <!-- X/ row-->
  </form>
</div>
<?php }else{ ?>
<div class="alert alert-info text-center" role="alert">
  <p><i class="fas fa-info fa-5x"></i></p>
  <h4 class="alert-heading">Registrar datos de empresa</h4>
  <p class="mb-0">Registrar datos de empresa, para usar este modulo</p>
</div>
<?php } ?>