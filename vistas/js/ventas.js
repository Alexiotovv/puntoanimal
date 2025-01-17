 
/*====
  Input de buscar producto/servicio 
*/
$('.basicAutoComplete').autoComplete({
    resolver: 'custom',
    events: {
        search: function (qry, callback) {
            var urlajax = $('.FormularioAjax').attr("action");
            // console.log(qry);
            $.ajax(
                {
                url: urlajax,
                type: "POST",
                async: true,
                data: { 
                  "valorBusqueda": qry
                  },
                dataType:"json"
                }
            ).done(function (res) {
                // console.log(res);
                callback(res);
            });
        }

    }
});

/*====
  Input de precio del producto/servicio
*/

$('#txt_precio').on('change', function(){
  // Aqu�� puedes ejecutar el c��digo que desees cuando el contenido del input cambie
  let cantidad = $("#txt_cant_producto").val();
  calcularTotal(cantidad);
});

/*===
  seleccionar un producto/servicio , @param: item:object,  value,text
*/
$('.basicAutoComplete').on('autocomplete.select',function(evt,item){
  // console.log('todo: ',item);
  var serverurl = $('.FormularioAjax').attr("serverurl");
  var urlajax= serverurl+"ajax/inventarioAjax.php";
  
  if(item === void 0){
    // console.log("Sin seleccionar");
    limpiar_campos();
  }else{
    if(item.value!=0){
      $.ajax({
        url: urlajax,
        type: "POST",
        async: true,
        data: {
          "cod_prodstock": item.value
        },
        dataType:"json",
        success: function(response){
          if(response == "error") {
              limpiar_campos();
          }else{
            // MOSTRAR resultadosque coincidan
            // console.log(response);
            $("#txt_descripcion").html(response["prodserviNombre"]);         
            $("#txt_tipo").html(response["prodserviTipo"]);         
            $("#txt_existencia").html(response["prodserviStock"]);

            $("#txt_precio").val(response["prodserviPrecio"]);         
            $("#txt_precio_total").html(response["prodserviPrecio"]);         
            
            $("#txt_cant_producto").val('1');         

            // // Activar Cantidad
            $('#txt_cant_producto').removeAttr('disabled');
            $('#txt_precio').removeAttr('disabled');
            // // Mostar boton Agregar
            $('#add_product_venta').slideDown();
            calcularTotal(1);
      
          }
        },
        error: function(error) {
          console.log(error);
        }
      });
    }else{
      limpiar_campos();
      $('.basicAutoComplete').autoComplete('clear');
    }  
  }
  
});

/* ============================================
  *    calcular total cantidad,precio, @param:String, cantidad ingresada
  ============================================*/
function calcularTotal(cant){
  var precio_total = cant * $('#txt_precio').val();
    
  var existencia = parseInt($('#txt_existencia').html());
    
    // ---------------------
     const formatterDolar = new Intl.NumberFormat('en-US',{
      style: 'decimal',
      currency: 'USD'
    });
    // $("#txt_precio").html(formatterDolar.format(response["prodserviPrecio"]));         
            
    // ---------------------
    $('#txt_precio_total').html(formatterDolar.format(precio_total));
    // comprobar stock, aplica solo producto
    if($('#txt_tipo').html()=="Producto"){
        // Ocultar el boton Agregar si la cantidad es menor que 1
        // si -1 a no es un numero
        if ((cant < 1 || isNaN(cant)) || (cant > existencia)){
          $('#add_product_venta').slideUp();
          toastr.warning("Stock no disponible","Stock");
        }else {
          $('#add_product_venta').slideDown();
        }
    }
    
}
  /*=================================================
  *  Eventos al ingresar la cantidad de producto/servicio
  ========================================================*/
  $(".venta-cantidad").on({
    keyup: function() {
      calcularTotal($(this).val());
    },
    blur: function() { 
      calcularTotal($(this).val());
    },
    change: function(){
      calcularTotal($(this).val());
    }
  });



  /* ============================================
  *  agregar producto al detalle
  ============================================*/
  $('#add_product_venta').click(function(e) {
    
    e.preventDefault();
    var urlajax = $('.FormularioAjax').attr("action");
    // var urlajax= serverurl+"ajax/inventarioAjax.php";

    if ($('#txt_cant_producto').val() > 0) {
     
      let codproducto = $("input[name=txt_cod_producto]").val();
      let cantidad = $('#txt_cant_producto').val();
      let precio = $('#txt_precio').val();
      console.log(codproducto);
      console.log(cantidad);
      console.log(precio);
      
      $.ajax({
        url: urlajax,
        type: 'POST',
        async: true,
        //dataType: 'json', 
        data: {"addProductoDetalle":"addProductoDetalle",producto:codproducto,cantidad:cantidad, precio:precio},
        success: function(response) {
          //console.log("info",response);
          if (response != 'error') {
           
            var info = JSON.parse(response);
            // console.log(info);
            $('#detalle_venta').html(info.detalle);
            $('#detalle_totales').html(info.totales);

           
            
            // $('#txt_cod_producto').val('');

            limpiar_campos();
            $('.basicAutoComplete').autoComplete('clear');

          }else {
            toastr.error("Error de petici��n","No hay datos");
          }
          viewProcesar();
        },
        error: function(error) {

        }
      });
    }
  });

  // mostrar/ ocultar boton Procesar
  function viewProcesar() {
    if ($('#detalle_venta tr').length > 0){
      $('#btn_facturar_venta').show();
      $('#btn_anular_venta').show();
    }else {
      $('#btn_facturar_venta').hide();
      $('#btn_anular_venta').hide();
    }
  }
  /*==================================================
  *  Buscar en tabla temporal detalles,al cargar pagina venta @param: id: usuario logeado
  ====================================================*/
  function searchForDetalle(id){
    var urlajax = $('.FormularioAjax').attr("action");

    var user = id;
    $.ajax({
      url: urlajax,
      type: 'POST',
      async: true,
      data: {"searchForDetalle":"searchForDetalle","user":id},
      success: function(response) {
        // console.log(response);
        if (response == "error") {
          // console.log('Sin detalles temporales iniciar pagina');
        }else {
          var info = JSON.parse(response);
          $('#detalle_venta').html(info.detalle);
          $('#detalle_totales').html(info.totales);
        }
        viewProcesar();    
      },
      error: function(error) {

      }
    });

  }

  /*==================================================
  *  Eliminar fila en tabla temporal, @param: correlativo: clave primaria de registro
  ====================================================*/
  function del_product_detalle(correlativo){
    // var serverurl = $('.FormularioAjax').attr("serverurl");
    var urlajax = $('.FormularioAjax').attr("action");
    // var urlajax= serverurl+"ajax/inventarioAjax.php";

    var id_detalle = correlativo;
    $.ajax({
      url: urlajax,
      type: "POST",
      async: true,
      data: {"delProductoDetalle":"delProductoDetalle",id_detalle:id_detalle},
      success: function(response) {
        // console.log(response);
        if (response != 'error') {
          var info = JSON.parse(response);
          if(info.Alerta=="exitoo"){
            toastr.info("Producto quitado de detalle","Sin productos agregados");
            $('#detalle_venta').html('');
            $('#detalle_totales').html('');  
          }else{
            $('#detalle_venta').html(info.detalle);
            $('#detalle_totales').html(info.totales);
            $('#txt_cod_producto').val('');
            toastr.info("Producto quitado de detalle","Exito");
            // $('.selectpicker').selectpicker('refresh');
          }
          limpiar_campos();
        }else {
          // fallo consulta al procedimento almacenado del_detalle_temp
          toastr.error("Fallo consulta al servidor","Fallido");
        }
        viewProcesar();
      },
      error: function(error) {
        
      }
    });
  }

  /*------ limpiar campos de vista venta ----*/
  function limpiar_campos(){

    $('#txt_descripcion').html('-');
    $('#txt_tipo').html('-');
    $('#txt_existencia').html('-');
    $('#txt_cant_producto').val('0');
    $('#txt_precio').val('0.00');
    $('#txt_precio_total').html('0.00');

    // Bloquear cantidad
    $('#txt_cant_producto').attr('disabled','disabled');

    // Ocultar boton agregar
    $('#add_product_venta').slideUp();

  }

  /*==================================================
  *  Anular la venta en proceso
  ====================================================*/
  $('#btn_anular_venta').click(function(e) {
    e.preventDefault();

    var urlajax = $('.FormularioAjax').attr("action");
   
    var rows = $('#detalle_venta tr').length;
    // si hay product
    if (rows > 0) {
      $.ajax({
        url: urlajax,
        type: 'POST',
        async: true,
        data: {"anularVenta":"anularVenta"},
        dataType:"json",
        success: function(response) {
          if(response["Alerta"]==="success"){
            alerta_simple_toastr(response)
            location.reload();
          }else{
            alerta_simple_toastr(response);
          }

        },
        error: function(error) {

        }
      });
    }
  });
/*==================================================
  *  Procesar la venta , imprimir factura,ticket, o ninguna
  ====================================================*/
$('#btn_facturar_venta').click(function(e) {
  e.preventDefault();
  var urlajax = $('.FormularioAjax').attr("action");
  var dnicliente = $('#select-dueno').val();
  var tipoimprimir = $('input:radio[name=venta_imprimir]:checked').val();
  var tipopago = $('input:radio[name=venta_pago_reg]:checked').val();
  var tipo_comprobante=$("#tipo_comprobante option:selected").text();
  var num_comprobante = $("#num_comprobante").val();
  var serie_comprobante = $("#serie_comprobante").val();

  var rows = $('#detalle_venta tr').length;
  // si hay product
  if (rows > 0) {
    $.ajax({
      url: urlajax,
      type: 'POST',
      async: true,
      data: {
        "procesarVenta":"procesarVenta",
        dnicliente: dnicliente,
        tipopago: tipopago,
        tipo_comprobante: tipo_comprobante,
        num_comprobante: num_comprobante,
        serie_comprobante: serie_comprobante
      },
      dataType:"json",
      success: function(response) {
        // console.log(response);
        if (response["Alerta"]=="success") {
          alerta_simple_toastr(response);
          if(tipoimprimir=="Ticket"){
            generarticketPDF(response["Data"].dniCliente,response["Data"].idVenta);
            location.reload();
          }else if(tipoimprimir=="Factura") {
            generarPDF(response["Data"].dniCliente,response["Data"].idVenta);
            location.reload();
          }else{
           // refrescar
            location.reload();
          }
        
        }else{
          alerta_simple_toastr(response);
        }

      },
      error: function(error) {

      }
    });
  }
});


// }); // fin ready

/*===================================================
* generar el pdf de factura, @param: cliente:dni de cliente, factura: id de factura
*/
function generarPDF(cliente,factura) {
  var serverurl = $('.FormularioAjax').attr("serverurl");
  var ancho = 700;
  var alto = 800;
  //calcular posicion x, y para centrar la ventana
  var x = parseInt((window.screen.width/2) - (ancho / 2));
  var y = parseInt((window.screen.height/2) - (alto / 2));

  $url = serverurl+'factura/generaFactura.php?cl='+cliente+'&f='+factura;
  window.open($url,"Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}
/*===================================================
* generar el pdf ticket, @param: cliente:dni de cliente, factura: id de factura
*/
function generarticketPDF(cliente,factura) {
  var serverurl = $('.FormularioAjax').attr("serverurl");
  
  var ancho = 600;
  var alto = 800;
  //calcular posicion x, y para centrar la ventana
  var x = parseInt((window.screen.width/2) - (ancho / 2));
  var y = parseInt((window.screen.height/2) - (alto / 2));

  $url = serverurl+'factura/generaTicket.php?cl='+cliente+'&f='+factura;
  window.open($url,"Ticket","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}

  /*=============================================
  FORMATO AL PRECIO FINAL
  =============================================*/

  // $("#nuevoTotalVenta").number(true, 2);

  /*=============================================
  SELECCIONAR M�0�7TODO DE PAGO
  =============================================*/
$("input:radio[name=venta_pago_reg]").change(function(){

    var metodo = $(this).val();
    // console.log("change metodo de pago:"+metodo);
    if(metodo == "Efectivo"){
      
      $(".cajasMetodoPago").html(

         '<div class="col-xs-4 mb-2">'+ 

          '<div class="input-group">'+ 

            ''+ 

            '<input type="text" class="form-control" id="nuevoValorEfectivo" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="$ 0.00" maxlength="15" required>'+

          '</div>'+

         '</div>'+
         '<div class="col-xs-4 mb-2" id="EfectivoRes">'+

          '<div class="input-group">'+

            '<label for="EfectivoRestante">Faltante: </label>'+

            '<input type="text" class="form-control" id="EfectivoRestante" readonly>'+

          '</div>'+

         '</div>'+

         '<div class="col-xs-4" id="capturarCambioEfectivo" style="">'+

          '<div class="input-group">'+

            '<label for="nuevoCambioEfectivo">Cambio: </label>'+

            '<input type="text" class="form-control" id="nuevoCambioEfectivo" placeholder="000000" readonly required>'+

          '</div>'+

         '</div>'

       )

    }else{
       $('.cajasMetodoPago').html('');
    }

  });

  $("input:radio[name=venta_pago_reg]").each(function(){
    var metodo = $('input:radio[name=venta_pago_reg]:checked').val();
    console.log("metodo de pago each checked:"+metodo);
    if(metodo == "Efectivo"){
      
      $(".cajasMetodoPago").html(

         '<div class="col-xs-4 mb-2">'+ 

          '<div class="input-group">'+ 

            ''+ 

            '<input type="text" class="form-control" id="nuevoValorEfectivo" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="$ 0.00" maxlength="15" required>'+

          '</div>'+

         '</div>'+
         '<div class="col-xs-4 mb-2" id="EfectivoRes">'+

          '<div class="input-group">'+

            '<label for="EfectivoRestante">Faltante: </label>'+

            '<input type="text" class="form-control" id="EfectivoRestante" readonly>'+

          '</div>'+

         '</div>'+

         '<div class="col-xs-4" id="capturarCambioEfectivo" style="">'+

          '<div class="input-group">'+

            '<label for="nuevoCambioEfectivo">Cambio: </label>'+

            '<input type="text" class="form-control" id="nuevoCambioEfectivo" placeholder="000000" readonly required>'+

          '</div>'+

         '</div>'

       )

    }else{
       $('.cajasMetodoPago').html('');
    } 
  });


  /*=============================================
  CAMBIO EN EFECTIVO , selecciona radio
  =============================================*/
  $(".cajasMetodoPago").on("change", "input#nuevoValorEfectivo", function(){
    var efectivo = $(this).val();
    var forma = formatCurrency($(this), "blur");
    // console.log("efectivo intro: "+efectivo);
    var total_v = $('#total_txt').html();
    
    if(total_v === void 0){
      toastr.warning("Sin productos seleccionados","Tabla vacia");
      }else{
        var parse_efectivo = parseFloat(efectivo.replace(/[$,]/g,""));
        var parse_total = parseFloat(total_v.replace(/[$,]/g,""));
        console.log("EFECTIVO CAPTURADO: "+parse_efectivo);
        console.log("TOTAL CAPTURADO: "+parse_total);
        var nuevoCambioEfectivo = $('#nuevoCambioEfectivo');

        var cambio =  Number(parse_efectivo) - Number(parse_total);
        console.log("cambio: "+cambio);
        if(cambio<0){
          console.log("efectivo insuficiente");
          $("#EfectivoRestante").val(cambio);
          nuevoCambioEfectivo.val("");
          formatCurrency($("#EfectivoRestante"), "blur");
        }else{
          nuevoCambioEfectivo.val(cambio);
          $("#EfectivoRestante").val("");
          formatCurrency(nuevoCambioEfectivo, "blur");
        }
      // $("#nuevoTotalVenta").number(true, 2);
      }
    
  });

  $(".cajasMetodoPago").on("keyup", "input#nuevoValorEfectivo", function(){
    
    formatCurrency($(this));
  });

  $("#tipo_comprobante").change(marcarImpuesto);



  marcarImpuesto()

  function marcarImpuesto(){


  
    var tipo_comprobante=$("#tipo_comprobante option:selected").text();
    if (tipo_comprobante=='Factura') {

      $("#serie_comprobante").val("F001")
      // numFactura()
      //cargamos los items al celect comprobantes
      $.post("../ajax/ventaAjax.php",{obtenerconsecutivo:1,tipo:'Factura'}, function(c){ 
        let data = eval(`(${c})`);

        $("#num_comprobante").val(data.consecutivo);
      });
    }

    if (tipo_comprobante=='Ticket') {
      $.post("../ajax/ventaAjax.php",{obtenerconsecutivo:1,tipo:'Ticket'}, function(c){ 
        let data = eval(`(${c})`);

        $("#num_comprobante").val(data.consecutivo);
      });
      $("#serie_comprobante").val("T001")
    }

    if (tipo_comprobante=='Boleta') {
      $.post("../ajax/ventaAjax.php",{obtenerconsecutivo:1,tipo:'Boleta'}, function(c){ 
        let data = eval(`(${c})`);

        $("#num_comprobante").val(data.consecutivo);
      });
      $("#serie_comprobante").val("B001")
    }

    console.log(tipo_comprobante)

	// 	// $("#impuesto").val(impuesto);
	// 	mostrar_impuesto(); 
  //       no_aplica=impuesto;
  //       numFactura();
  //       numSerie();
	// 	// $("#serie_comprobante").val( "F001" );
	// }else if(tipo_comprobante=='Boleta'){
	// 	// $("#impuesto").val(impuesto);
	// 	mostrar_impuesto();
  //       no_aplica=impuesto;
  //       numBoleta();
	// 	numSerieBoleta();
	// 	// $("#serie_comprobante").val( "B001" );
	// }
	// else{
	// 	$("#impuesto").val("0");
  //       no_aplica=0;
  //       numTicket();
	// 	numSerieTicket();
	// 	// $("#serie_comprobante").val( "P001" );
	// }


	// console.log("hola mundo");
  }

  function numFactura(){

    var idsucursal = $("#idsucursal").val();
  
    $.ajax({
    url: '../controladores/venta.php?op=mostrarf',
    data:{idsucursal: idsucursal},
    type:'get',
    dataType:'json',
    success: function(d){
        iva=d;
        $('#porcentaje').attr('disabled', true)
    $("#num_comprobante").val( ('0000000' + iva).slice(-7) ); // "0001"
    $("#nFacturas").html( ('0000000' + iva).slice(-7) ); // "0001"
    }
      });}