<div class="titulo-linea mt-2">
  <h2><i class="far fa-file-alt"></i>Venta</h2>
  <hr class="sidebar-divider">
</div>
<?php 
 	 	
  require_once "./controladores/empresaControlador.php";
  $iEm=new empresaControlador();
  //conteo de empresa
  $cEm=$iEm->datos_empresa_controlador("Unico");
  if($cEm->rowCount()==1){
    // $campos=$cEm->fetch();
    
 ?>
<div class="intro mb-4">
  <div class="FormularioAjax" action="<?php  echo SERVERURL; ?>ajax/ventaAjax.php" serverurl="<?php  echo SERVERURL; ?>" >
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-12">
            <h3 class="sub-titulo-panel"><i class="fas fa-file-invoice-dollar"></i>Nueva Venta</h3>
            <hr>
          </div>
          <!-- cliente -->
          <div class="col-6 mb-2" id="venta-cliente-db">
            <span>Cliente</span>

            <div class="d-flex flex-row mascota-dueno align-items-center mt-2">
              <img style="width: 55px; height: 55px;" id="imgpreve-cliente-m" class="rounded-circle mr-2" src="<?php  echo SERVERURL;?>vistas/images/general/user-foto.svg" alt="fotocliente">
              <select class="selectpicker w-100"  name="mascota-dueno" id="select-dueno" serverurl="<?php echo SERVERURL; ?>" data-show-subtext="true" data-live-search="true">
                <!-- ***dinamico***(cargar cliente,input key buscar) -->
                
                <!-- *x**dinamico**x* -->
              </select>
              <!-- ir agregar nuevo cliente -->
              <a href="<?php  echo SERVERURL;?>addCliente/venta" class="btn btn-primary ml-2 btn-circle btn-xs">
              <i class="fas fa-plus"></i>
              </a>
            </div>

          </div>
          <!-- x cliente x -->
          <div class="col-md-6">
            <div class="d-flex flex-column float-right">
              <span>Imprimir</span>
              <div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" value="Ticket" id="radio1" name="venta_imprimir" class="custom-control-input" checked="">
                  <label class="custom-control-label" for="radio1">Ticket</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" value="Factura" id="radio2" name="venta_imprimir" class="custom-control-input">
                  <label class="custom-control-label" for="radio2">Factura</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" value="Ninguno" id="radio3" name="venta_imprimir" class="custom-control-input">
                  <label class="custom-control-label" for="radio3">Omitir</label>
                </div>  
              </div>
            </div>
          </div>
          <!-- buscar producto/servicio -->
          <div class="col-lg-12 mb-4">
            <hr>
            <span>Agregar Producto/Servicio</span><br>
            <div class="table-responsive">
              <table class="table">
                <thead class="">
                  <tr>
                      <th width="100">Código</th>
                      <th>Des.</th>
                      <th>Tipo</th>
                      <th>Stock</th>
                      <th width="150">Cantidad</th>
                      <th class="textright">Precio</th>
                      <th class="textright">Precio Total</th>
                      <th>Acciones</th>
                  </tr>
                  <tr class="bg-light">
                      <!-- BUSCAR PRODUCTO/SERVICIO -->
                      <td width="30%" id="select-prodservi">
                        <!-- simple_select -->
                        <select class="form-control basicAutoComplete" name="txt_cod_producto"
                          placeholder="Buscar Producto por nombre o codigo"
                          autocomplete="off" id="txt_cod_producto"></select>

                      </td>
                      <!-- --x --- -->
                      <td id="txt_descripcion">-</td>
                      <td id="txt_tipo">-</td>
                      <td id="txt_existencia">-</td>
                      <td>
                        <input type="number" class="venta-cantidad" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled>
                      </td>
                      <td class="textright">
                        <div class="input-group">
                          <input id="txt_precio" type="number" class="form-control" value="0.00" disabled>
                          <div class="input-group-append">
                            <span class="input-group-text">$</span>
                          </div>
                        </div>   
                      </td>
                      <td id="txt_precio_total" class="textright">0.00</td>
    
                      <td><a href="#" id="add_product_venta" class="btn btn-dark" style="display: none;">Agregar</a></td>
                  </tr>  
                </thead>
              </table>
              
            </div>
            
          </div>
          <!-- x-buscar producto/servicio-x -->

          <!-- table produc/Servicio -->
          <div class="col-12">
            <!--  -->
            <div class="table-responsive table-sm overflow-auto scroll-style-1" style="max-height: 300px;">
              <!--  -->
              <table class="table table-bordered ">
                <thead>
                  <tr>
                    <!-- Set columns width -->
                    <th class="text-center">Codigo</th>
                    <th class="text-center">Producto/Servicio</th>
                    <th class="text-center" style="width: 100px;">Precio</th>
                    <th class="text-center" style="width: 150px;">Cantidad</th>
                    <th class="text-center" style="width: 100px;">Total</th>
                    <th class="text-center" style="width: 40px;">Accion</th>
                  </tr>
                </thead>
                <tbody id="detalle_venta" >
                  <!-- ***** AJAX DETALLES ****************************** -->
                   
                    <!-- ***** AJAX DETALLES *****X*****X******X************** -->
                </tbody>
                
              </table>
            </div>
            <div class="venta-footer d-flex flex-row justify-content-between">
              <div class="mt-4">
                <small>Metodo de pago</small>
                <div class="tipo-pago d-flex flex-wrap">
                  <label for="venta-efectivo" class="d-flex flex-column">
                    <input type="radio" name="venta_pago_reg" class="pago-item" id="venta-efectivo" value="Efectivo" checked />
                    Efectivo
                    <i class="fas fa-money-bill-alt"></i>
                  </label>
                
                 
                </div>

                




                <div class="cajasMetodoPago">
                <!-- ****** CAJAS METODO PAGO ******* -->
                </div>
                
              </div>

  

              <div class="form-group mb-2">
                <label class="col-form-label">Tipo Comprobante:</label>
                <div class="input-group">
                
                    <span class="input-group-addon"></span>
                    
                    <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required>
                      <option value=Boleta>Boleta</option>
                      

                    </select>
                  </div>
                </div>

                <div class="form-group mt-4">
                  <div class="row">
                    <div class="col-md-4">
                      <label class="input-group">Serie:</label>
                      <input style=" text-align:center; border-radius:10px;" type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="7" placeholder="Serie" readonly="">
                </div>

                <div class="col-md-8">
                      <label class="input-group">Número:</label>
                      <input style=" text-align:center; border-radius:10px;" type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="10" placeholder="Número" required="" readonly="">
                    </div>
                </div>
              </div>




              <!-- --**** AJAX RESULTADOS TOTALES *************************** -->
              <div id="detalle_totales">
                  
              </div>
              <!-- --**** AJAX RESULTADOS TOTALES **X***X*******X***X******** -->
              
            </div>
        
          </div>
          <!-- --X-- table produc/Servicio --X--- -->
      
        </div>
      </div>
      <div class="col-lg-12 text-center mt-2">
        <button id="btn_anular_venta" class="btn btn-warning">Anular</button>                    
        <button id="btn_facturar_venta" class="btn btn-primary ml-2" style="display: none;">Guardar</button>      
        <a href="<?php  echo SERVERURL;?>listaVenta/" class="btn btn-primary">Lista Ventas</a>               
      </div>
    </div>
  </div>

  <script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function(event){
      
      var usuarioid = '<?php echo $_SESSION['id_vetp']; ?>';
     searchForDetalle(usuarioid);
   });

  </script>
</div>
<?php }else{ ?>
<div class="alert alert-info text-center" role="alert">
  <p><i class="fas fa-info fa-5x"></i></p>
  <h4 class="alert-heading">Registrar datos de empresa</h4>
  <p class="mb-0">Registrar datos de empresa, para usar este modulo</p>
</div>
<?php } ?>