<div class="titulo-linea mt-2">
  <h2><i class="far fa-file-alt"></i>Lista Ventas</h2>
  <hr class="sidebar-divider">
</div>
<div class="intro mb-4">
  <div class="row">
    <div class="col-12 mb-4">
      <div class="row">
        <?php
        if (isset($_SESSION['fecha_inicio_venta']) == false && isset($_SESSION['fecha_final_venta']) == false) {
          $btn_mostrar_todos = "";
          $btn_lista_pdf = "";
           $btn_lista_excel = "";
          $data = [
            "finicio" => "",
            "ffinal" => "",
            "ttipoc" => ""
          ];
        } else {
          // formato fecha: aaaa-mm-dd 
          $data = [
            "finicio" => $_SESSION['fecha_inicio_venta'],
            "ffinal" => $_SESSION['fecha_final_venta'],
            "ttipoc" => $_SESSION['tipo_comprobante_venta']
          ];

          $btn_lista_pdf = '<a class="btn btn-style-2 btn-sm ml-2 mb-2" href="' . SERVERURL . 'report/generarReportsLista.php?cl=venta&fi=' . $data['finicio'] . '&ff=' . $data['ffinal'] . '&tp='. $data['ttipoc'] . '" target="bland"><i class="fas fa-file-pdf mr-2"></i>PDF</a>';
          $btn_lista_excel = '<a class="btn btn-style-3 btn-sm ml-2 mb-2" href="' . SERVERURL . 'report/generarExcel.php?cl=venta&fi=' . $data['finicio'] . '&ff=' . $data['ffinal'] . '&tp='. $data['ttipoc'] . '" target="bland"><i class="fas fa-file-excel mr-2"></i>EXCEL</a>';

          $btn_mostrar_todos = '<button type="submit" class="btn btn-secondary ml-2 btn-circle btn-xs" data-toggle="tooltip" title="Mostrar todos"><i class="fas fa-file-alt"></i></button>';
        }
        ?>
        <div class="col-md-6 mb-2">
          <form class="Formulario_ajax_simple" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" autocomplete="off">
            <div class="d-flex flex-row">
              <input type="hidden" name="modulo" value="venta">
              <div class="input-group date group mb-0 mt-0 w-100 fechas" id="">
                <input type="text" name="fecha_inicio" id="fecha" class="p-0" placeholder="Fecha de inicio"  serverurl="<?php echo SERVERURL; ?>"/>
                
                <div class="input-group-addon input-group-append">
                  <div class="input-icon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
              </div>
              <div class="input-group date group mb-0 mt-0 w-100 ml-2 fechas" id="">
                <input type="text" name="fecha_final" class="p-0" placeholder="Fecha final" />
                <div class="input-group-addon input-group-append">
                  <div class="input-icon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
              </div>
              
              <div class="form-group mb-0 mt-0 w-100 ml-2">
                <div class="input-group">
                
                    <span class="input-group-addon"></span>
                    
                    <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker">
                      <option value=Todos>Todos</option>
                      <option value=Boleta>Boleta</option>
        

                    </select>
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
                <input type="hidden" name="modulo" value="venta">
                <?php echo $btn_mostrar_todos; ?>
              </form>
            </div>
            <div class="col-md-8">
              <div class="float-right">
                <!-- BTN IMPRIMIR REPORT VENTA POR FECHA -->
                <?php echo $btn_lista_pdf; ?>
               <?php echo $btn_lista_excel; ?>
                <a href="<?php echo SERVERURL; ?>addNuevaVenta/" class="btn btn-primary btn-sm ml-2 mb-2"><i class="fas fa-plus mr-2"></i>Nueva Venta</a>

              </div>

            </div>
          </div>

        </div>

      </div>

    </div>
    <div class="col-12">
      <!-- tabla y paginador -->
      <?php
      require_once "./controladores/ventaControlador.php";
      $ins_venta = new ventaControlador();
      // pagina: views
      echo $ins_venta->paginador_venta_controlador($pagina[1], 10, $_SESSION['privilegio_vetp'], $pagina[0], $data);
      ?>

    </div>
  </div> <!-- row  -->

</div>
<!-- - MODAL- DETALLE FACTURA - -->
<div class="modal fade" id="modalDetalleFactura" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
    </div>
  </div>
</div>
<!-- -X- MODAL- DETALLE FACTURA -X- -->

<script>
  function EnviarSunat(tipoc, idventa, idcol) {
     
    let serverurl= $('#fecha').attr("serverurl");
    console.log(serverurl);
    if (tipoc == 1) {
      $url = serverurl +'/FACT_WebService/Facturacion/boleta.php?idventa=';
    } else {
      $url = serverurl +'/FACT_WebService/Facturacion/factura.php?idventa=';
  
    }

     $.ajax({

      url: $url + idventa + '&codColab=' + idcol,

      type: 'get',
      dataType: 'text',
      beforeSend: function() {

        $(".modal").show();

      },
      success: function(resp) {

        Swal.fire({
          title: 'SUNAT',
          type: 'success',
          text: resp
        });

        setTimeout('document.location.reload()',4000);

      },
      complete: function() {
        $(".modal").hide();
      }

    }); 

  }
</script>