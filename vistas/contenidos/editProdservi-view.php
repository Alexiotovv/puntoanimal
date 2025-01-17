<div class="titulo-linea mt-2">
  <h2><i class="far fa-user"></i>Productos y Servivios</h2>
  <hr class="sidebar-divider">
</div>
 <?php 
    require_once "./controladores/empresaControlador.php";
    require_once "./controladores/inventarioControlador.php";
    
    $iEm=new empresaControlador();

    $cEm=$iEm->datos_empresa_controlador("Unico");
    if($cEm->rowCount()==1){
        $campos=$cEm->fetch();
      

    $inst_inventario = new inventarioControlador();

    $datos_inventario=$inst_inventario->datos_inventario_controlador("Unico",$pagina[1]);

    if($datos_inventario->rowCount()==1){
      $camposi=$datos_inventario->fetch();
      // $precio_forma=number_format($camposi['prodserviPrecio'],2);
    
 ?>
<div class="intro mb-4">
  <form class="FormularioAjax" action="<?php  echo SERVERURL; ?>ajax/inventarioAjax.php" method="POST" data-form="update" id="form-add-Cliente">
    <input type="hidden" name="inventario_cod_up" value="<?php echo $ins_loginc->encryption($camposi['codProdservi']); ?>">
    <div class="row">
      <!-- INF GENERAL -->
      <div class="col-lg-12">
        <h3 class="sub-titulo-panel">
          <i class="fas fa-edit mr-2"></i>
            Editar Inventario</h3>
            <small class="float-right"><b><?php echo $camposi['codProdservi']; ?></b></small>
        <div class="row mt-2">
          <div class="col-md-6">
            <div class="group">
              <input  type="text"  name="prodservi_nombre_edit" value="<?php echo $camposi['prodserviNombre']; ?>" required=""/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <select name="prodservi_tipo_edit" id="prodservi_tipo">
                <option value="Producto" <?php if($camposi['prodserviTipo']=="Producto"){echo 'Selected=""';} ?> >Producto</option>
                <option value="Servicio" <?php if($camposi['prodserviTipo']=="Servicio"){echo 'Selected=""';} ?> >Servicio</option>
              </select>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Tipo</label>
            </div>
          </div>
          <div class="col-md-6 d-flex flex-row">
            <div class="group w-75">
              <input type="text" name="prodservi_precio_edit" id="prodservi_precio" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" value="<?php echo $camposi['prodserviPrecio']; ?>" data-type="currency" placeholder="$ 0.00" maxlength="15">
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Precio</label>
            </div>
            <samp class="mt-5 ml-2 text-lg"><?php echo $campos['empresaMoneda']; ?></samp>
          </div>
   <!--        <div class="col-md-6">
            <div class="group">
            <input type="text" name="prodservi_vencimiento_edit" value="<?php echo $camposi['fVencimiento']; ?>"  id="inventario_vencimiento" />
                <span class="highlight"></span>
              <span class="bar"></span>
              <label>F. Vencimiento</label>
            </div>
          </div> -->

          <div class="col-md-6">
                  <div class="input-group date group fechas">
                    <input type="text" name="prodservi_vencimiento_edit" value="<?php echo $camposi['fVencimiento']; ?>" id="inventario_vencimiento" required=""/>
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
              <input type="number" class="input-sniperr" name="prodservi_stock_edit" value="<?php echo $camposi['prodserviStock']; ?>" min="0" id="inventario-stock" >
            </div>
          </div>
        </div>
      </div>
     
      <div class="col-lg-12 text-center mt-5">
        <button type="submit" class="btn btn-primary">Editar</button>      
        <a href="<?php  echo SERVERURL;?>listaProdservi/" class="btn btn-primary">Lista ProdServi</a>            
      </div>

    </div> <!-- X/ row-->
  </form>
</div>

<?php }else{ ?>
<div class="alert alert-danger text-center" role="alert">
  <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
  <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
  <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
</div>
<?php } ?>

<?php }else{ ?>
<div class="alert alert-info text-center" role="alert">
  <p><i class="fas fa-info fa-5x"></i></p>
  <h4 class="alert-heading">Registrar datos de empresa</h4>
  <p class="mb-0">Registrar datos de empresa, para usar este modulo</p>
</div>
<?php } ?>