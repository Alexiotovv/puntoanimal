/*==================================================================*/

// buscar en DB al pulsar tecla en input de select dueño
$(document).on('keyup', '.mascota-dueno .bs-searchbox input', function (e) {
  var valorBusqueda = $(this).val();
  console.log(valorBusqueda);
  // buscar si escribe numero o letras
  if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90)) {
    cargarDuenoMascota(valorBusqueda);

  } else if (e.keyCode >= 37 && e.keyCode <= 40) {
    // console.log("no es numero o letra");
    e.preventDefault();
    e.stopPropagation();
    return false;
  } else {
    if (valorBusqueda == "") {
      cargarDuenoMascota("");
      console.log("cargar los ultimos 5 DB-cliente");
    }
    e.preventDefault();
    e.stopPropagation();
    // console.log("no es numero o letra");
    return false;
  }

});

/* Cargar en select dueño de mascota
* @param: valor: cedula/DNI de cliente
*/
function cargarDuenoMascota(valor) {
  var serverurl = $('.mascota-dueno .selectpicker').attr("serverurl");
  let url = serverurl + 'ajax/clienteAjax.php';
  console.log(url);
  let buscar = valor;
  $.ajax({
    url: url,
    type: 'POST',
    data: {
      "valorBuscar": buscar
    },
    success: function (response) {
      // console.log(response);
      $("#select-dueno").html(response);
      $('.selectpicker').selectpicker('refresh');
      if (response != " ") {
        var duenodni = $("select[name=mascota-dueno] option:selected").val();
        console.log("mi cliente:  " + duenodni);
        var elegidofoto = $('select[name=mascota-dueno] option:selected').data('foto');
        $('#imgpreve-cliente-m').attr('src', elegidofoto);
        $('.selectpicker').selectpicker('refresh');

        // ----- ADD CITA CARGAR MASCOTA/s DE CLIENTE -------
        if (addcitapage == "SI") {
          // buscar mascota/s de cliente
          cargarMascotaSelect(duenodni);
        }
        // ----X--- ADD CITA CARGAR MASCOTA/s DE CLIENTE  ----X--

      } else {
        $('#imgpreve-cliente-m').attr('src', serverurl + 'vistas/images/general/user-foto.svg');
        // console.log("Sin resultados desde DB-cliente");
      }
    }
  });

}

/*=======================================================
agregar STOCK PRODUCTO - buscar COD producto y mostrar en modal
=======================================================*/
$(".table").on("click", ".btn-agregar-stock", function () {

  var cod_prodstock = $(this).attr("id_prod");
  var serverurl = $(this).attr("serverurl");
  var urlajax = serverurl + "ajax/inventarioAjax.php";
  console.log("clic ajax id_prod : " + cod_prodstock);
  console.log("server ajax : " + urlajax);
  var datos = new FormData();
  datos.append("cod_prodstock", cod_prodstock);

  $.ajax({
    url: urlajax,
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      $("#cod_prod_up").val(respuesta["codProdservi"]);
      $("#txt_prod_up").text(respuesta["prodserviNombre"]);
    }
  });

});

/*=======================================================
EDITAR ESPECIE - buscar especie a editar mostrar en modal
=======================================================*/

$(".table").on("click", ".btnEditarEspecie", function () {

  var id_especie = $(this).attr("id_especie");
  var serverurl = $(this).attr("serverurl");
  var urlajax = serverurl + "ajax/especieAjax.php";
  console.log("clic ajax id_especie : " + id_especie);
  console.log("server ajax : " + urlajax);
  var datos = new FormData();
  datos.append("id_especie", id_especie);

  $.ajax({
    url: urlajax,
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      // console.log(respuesta);
      $("#id_especie_id").val(respuesta["idEspecie"]);
      $("#id_especie_nombre").val(respuesta["espNombre"]);
    }
  });

});

/*=======================================================
EDITAR RAZA - buscar raza a editar mostrar en modal
=======================================================*/

$(".table").on("click", ".btnEditarRaza", function () {

  var id_raza = $(this).attr("id_raza");
  var serverurl = $(this).attr("serverurl");
  var urlajax = serverurl + "ajax/razaAjax.php";
  console.log("clic ajax id_raza : " + id_raza);
  // console.log("server ajax : "+urlajax);
  var datos = new FormData();
  datos.append("id_raza", id_raza);

  $.ajax({
    url: urlajax,
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      // console.log(respuesta);
      $("#id_raza_edit").val(respuesta["idRaza"]);
      $("#id_raza_nombre").val(respuesta["razaNombre"]);
      $("#id_raza_especie").val(respuesta["idEspecie"]);
    }
  });

});

/*==================================================================*/
/*  BUSCAR RAZA, cargar raza segun especie seleccionada
/*==================================================================*/

$(document).on('change', '#mascota_especie', function () {
  recargarRaza("");
});


function recargarRaza(edit) {
  var especie = $("select[name=mascota_especie_reg]").val();
  // console.log("select change mascota especie: "+especie);

  var serverurl = $('#mascota_especie').attr("serverurl");
  var urlajax = serverurl + "ajax/razaAjax.php";
  // console.log("server SElect ajax : "+urlajax);

  $.ajax({
    url: urlajax,
    method: "POST",
    data: "id_especie=" + especie,
    success: function (respuesta) {
      // console.log(respuesta);
      $("#mascota_raza").html(respuesta);
      $('.selectpicker').selectpicker('refresh');
      var elegido2 = $('select[name=mascota_raza_reg]').val();
      console.log("select raza change : " + elegido2);
      $('.selectpicker').selectpicker('refresh');

      // -- EDITAR MASCOTA -- RAZA id -- marcar raza actual
      if (edit != "") {
        // console.log("edit no blanco : "+edit);
        $("#mascota_raza option[value=" + edit + "]").attr("selected", true);
        $('.selectpicker').selectpicker('refresh');
      }

    }
  });
}

/*==================================================================*/
/*  INICIALIZAR CAMPOS
/*==================================================================*/
//  variable inicializar, usar cuando se edita una cita 
var citaedit = "false";
//  variable inicializar, usar cuando se agrega una cita, mostrar mascota de cliente
var addcitapage = "false";

$(document).ready(function () {
  var raza_pag_mascota = $('select[name=mascota_especie_reg]').val();
  var mascota_pag_dueno = $("select[name=mascota-dueno]").val();
  var perfil_dueno = $('input[name=perfil_id_dni]').val();
  // console.log("raza de select mascota:  "+raza_pag_mascota);
  // console.log("dueño select mascota:  "+mascota_pag_dueno);
  if (raza_pag_mascota === void 0) {
    // console.log("variable no declarada: ");
  } else {
    // console.log("-----PAGINA ADDMASCOTA:ADDdueño----");
    recargarRaza("");
  }
  // cargar nueva mascota desde perfil cliente btn nueva
  if (perfil_dueno === void 0) {
    if (mascota_pag_dueno === void 0) {
    } else {
      cargarDuenoMascota("");
    }
  } else {
    cargarDuenoMascota(perfil_dueno);
  }
  /* ===============================================================
  *   ADD CITA
  */
  // add cita cargar las mascotas segun cliente
  var addcita = $('select[name=cita_paciente_reg]').val();
  if (addcita === void 0) {
  } else {
    // console.log("AGREGAR CITA CARGAR DUEÑO MASCOTA");
    addcitapage = "SI";
    cargarDuenoMascota("");
  }


  /* ===============================================================
  *   EDITAR MASCOTA - mostrar dueño actual, raza actual 
  */
  var edit_dueno = $('input[name=dueno_id_dni]').val();
  var edit_raza_id = $("input[name=id_raza_edit]").val();
  if ((edit_dueno === void 0) && (edit_raza_id === void 0)) {
    // console.log("-----NO EDITAR MASCOTA----");
  } else {
    // console.log("-----PAGINA EDITAR-MASCOTA:----");
    // console.log("form edit mascota dueno dni: "+edit_dueno);
    cargarDuenoMascota(edit_dueno);
    // marcar raza actual
    recargarRaza(edit_raza_id);
  }
  /* ===============================================================
  *   EDITAR CITA - seleccionar cliente y mascota 
  */
  var cliente = $('input[name=cita_cliente_dni]').val();
  var paciente = $("input[name=cita_paciente_cod]").val();
  if ((cliente === void 0) && (paciente === void 0)) {
    // console.log("-----NO EDITAR CITA----");
  } else {
    //  ----- PAGINA EDIT CITA  -----
    //  console.log("----- EDITAR CITA----");
    citaedit = "SI";
    cargarDuenoMascota(cliente);
    // marcar mascota de cliente
    cargarMascotaSelect(cliente);

  }

  // Construir la URL completa
  var serverurl = $('.historia_codveterinario_reg .selectpicker').attr("serverurl");
  var targetURL = serverurl + 'addHistorialM/';

  // Comprobar si la URL actual coincide con la URL específica
  if (window.location.href.indexOf(targetURL) === 0) {
    console.log("cargar veterinarios");
    cargarVeterinarioSelect("",serverurl);
  }

  //agregar código de listado de citas para hoy
  let urlCita = $('.citas-para-hoy .citas-hoy').attr("serverurl");
  console.log(urlCita);
  cargarCitasParaHoy(urlCita);

  
  //agregar código de listado de proximapara hoy
  let urlVacuna = $('.vacuna-proxima .vacunas-prox').attr("serverurl");
  console.log(urlVacuna);
  cargarVacunasProximas(urlVacuna);

    //agregar vencimiento
    let urlVencida = $('.producto-vencido .productos-vencido').attr("serverurl");
    console.log(urlVencida);
    cargarProductosVencidos(urlVencida);
});  //$(document).ready fin



/*==================================================================*/
/*  //Buscar por ruc y dni sunat
/*==================================================================*/

$('#buscar').click(function(){
  let serverurl = $('#buscar').attr("serverurl");
  let dni=$('#cliente_dni').val();
  console.log(dni);

  $.ajax({
    
    url:serverurl+'controladores/sunatcontroller.php',
    type:'post',
    data: 'dni='+dni,
    dataType:'json',
    success:function(r){
     console.log(r);
       if (r.numeroDocumento==dni) {
           if (dni.length <=8) {
            console.log("entro dni");
            $('#cliente_apellido').val(r.apellidoPaterno + " " + r.apellidoMaterno);
            $('#cliente_nombre').val(r.nombres);
           } else {
            console.log("entro ruc");
            $('#cliente_direccion').val(r.direccion);
            $('#cliente_nombre').val(r.razonSocial);
           }
             
       } else {
           alert('error');
       }
    }
  });
});


/*==================================================================*/
/*  ADD CITA, cargar mascota segun cliente
/*==================================================================*/
$(document).on('change', '.add-cita-form #select-dueno', function () {
  cargarMascotaSelect($(this).val());
  // console.log("select change change otro: "+$(this).val());

});

$(document).on('change', '.add-historial-mct #select-veterinario', function () {
  console.log("cargar veterinarios");
  let urlFoto = $(this).find('option:selected').data('foto');
  console.log(urlFoto);
  cargarVeterinarioFoto(urlFoto);
  console.log("select change change otro: "+urlFoto);

});

/* Cargar mascota en select segun cliente seleccionado
*  @param: masco: dni del cliente
*/
function cargarMascotaSelect(masco) {
  var serverurl = $('.FormularioAjax').attr("action");
  var server = $('.FormularioAjax').attr("server");
  //console.log("MI SEREV AJAX "+serverurl);
  $.ajax({
    url: serverurl,
    method: "POST",
    data: "dni_cliente=" + masco,
    success: function (respuesta) {
      // console.log(respuesta);
      $("#cita-paciente").html(respuesta);
      $('.selectpicker').selectpicker('refresh');
      var elegido = $('select[name=cita_paciente_reg]').val();
      // console.log("select codigo mascota change : "+elegido);
      $('.selectpicker').selectpicker('refresh');
      // sin resultados option value=0
      if (elegido == 0) {
        $('#imgpreve-mascota-cita').attr('src', server + 'vistas/images/general/mascota-foto.svg');
      }
      // cambiar foto
      var elegido2 = $('select[name=cita_paciente_reg] option:selected').data('foto');
      $('#imgpreve-mascota-cita').attr('src', elegido2);


      // PAGINA EDITAR CITA--
      if (citaedit == "SI") {
        var pacienteinput = $("input[name=cita_paciente_cod]").val();
        $("#cita-paciente option[value=" + pacienteinput + "]").attr("selected", true);
        // cambiar foto
        var elegido2 = $('select[name=cita_paciente_reg] option:selected').data('foto');
        $('#imgpreve-mascota-cita').attr('src', elegido2);
        // $('select[name=cita_paciente_reg]').val();
        // console.log("EDITAR PACIENTE: SELCT: "+$('select[name=cita_paciente_reg]').val());
        $('.selectpicker').selectpicker('refresh');
      }

    }
  });

}

/* Cargar veterinario en select segun historial seleccionado
*  @param: veterinario: vet del cliente
*/

function cargarVeterinarioSelect(valor, mainURL) {
  let url = mainURL + 'ajax/veterinarioAjax.php';
  console.log(url);
  let buscar = valor;
  $.ajax({
    url: url,
    type: 'POST',
    data: {
      "valorBuscar": buscar
    },
    success: function (response) {
      // console.log(response);
      $("#select-veterinario").html(response);
      $('.selectpicker').selectpicker('refresh');
      if (response != " ") {
        var vetdni = $("select[name=historia_codveterinario_reg] option:selected").val();
        console.log("mi veterinario:  " + vetdni);
        var elegidofoto = $('select[name=historia_codveterinario_reg] option:selected').data('foto');
        console.log("mi foto:  " + elegidofoto);
        $('#imgpreve-vet-cita').attr('src', elegidofoto);
        $('.selectpicker').selectpicker('refresh');

      } else {
        $('#imgpreve-vet-cita').attr('src', serverurl + 'vistas/images/general/user-foto.svg');
        // console.log("Sin resultados desde DB-cliente");
      }
    }
  });

}

function cargarVeterinarioFoto(valor) {
  if(valor){
    let elegidofoto = valor;
    console.log("mi foto:  " + elegidofoto);
    $('#imgpreve-vet-cita').attr('src', elegidofoto);
    $('.selectpicker').selectpicker('refresh');
  }else{
    $('#imgpreve-vet-cita').attr('src', serverurl + 'vistas/images/general/user-foto.svg');
  }

}

// Carga las citas para hoy en las notificaciones
function cargarCitasParaHoy(mainURL) {
  //let url = $('.FormularioAjax').attr("action");
  let url = mainURL + 'ajax/citaHoyAjax.php';
  console.log(url);
  $.ajax({
    url: url,
    type: 'POST',
    data: {
      "fecha_hoy": "fecha_hoy"
    },
    success: function (response) {
      // console.log(response);
      $("#citas-hoy").html(response);
      let cantidad = $("#cantidad_notificaciones").val();
      $("#numero_notificaciones").html(cantidad);
    }
  });

}

// Carga las citas para hoy en las notificaciones
function cargarVacunasProximas(mainURL) {
  //let url = $('.FormularioAjax').attr("action");
  let url = mainURL + 'ajax/citaHoyAjax.php';
  console.log(url);
  $.ajax({
    url: url,
    type: 'POST',
    data: {
      "prox_vacuna": "prox_vacuna"
    },
    success: function (response) {
      // console.log(response);
      $("#vacunas-prox").html(response);
      let cantidad = $("#cantidad_notificaciones_vac").val();
      $("#numero_notificaciones_prox_vacuna").html(cantidad);
    }
  });

}

// Carga las prod-vencidos para hoy en las notificaciones
function cargarProductosVencidos(mainURL) {
  //let url = $('.FormularioAjax').attr("action");
  let url = mainURL + 'ajax/citaHoyAjax.php';
  console.log(url);
  $.ajax({
    url: url,
    type: 'POST',
    data: {
      "prod_vencido": "prod_vencido"
    },
    success: function (response) {
      // console.log(response);
      $("#productos-vencido").html(response);
      let cantidad = $("#cantidad_notificaciones_ven").val();
      $("#numero_notificaciones_prod_vencido").html(cantidad);
    }
  });

}

/* ==================================================================
*   PERFIL CLIENTE- MASCOTAS DETALLE MODAL
*/

$(".lista-mascotas .list-group").on("click", ".lista-detalle", function () {

  var id_masc = $(this).attr("id_masc");
  var serverurl = $(this).attr("serverurl");
  // var serverurl = $(this).data("serverurl");
  var urlajax = serverurl + "ajax/mascotaAjax.php";
  console.log("clic ajax id_mascota : " + id_masc);
  // console.log("server ajax : "+urlajax);
  var datos = new FormData();
  datos.append("id_masc", id_masc);

  $.ajax({
    url: urlajax,
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      // console.log(respuesta);
      $("#modalDetalleMascota #modal_codigo").text(respuesta["codMascota"]);
      $("#modalDetalleMascota #modal_nombre").text(respuesta["mascotaNombre"]);
      $("#modalDetalleMascota #modal_especie").text(respuesta["espNombre"]);
      $("#modalDetalleMascota #modal_raza").text(respuesta["razaNombre"]);
      $("#modalDetalleMascota #modal_fecha").text(respuesta["mascotaFechaN"]);
      $("#modalDetalleMascota #modal_peso").text(respuesta["mascotaPeso"]);
      $("#modalDetalleMascota #modal_sexo").text(respuesta["mascotaSexo"]);
      $("#modalDetalleMascota #modal_castrado").text(respuesta["castrado"]);
      $("#modalDetalleMascota #modal_color").text(respuesta["mascotaColor"]);
      $("#modalDetalleMascota #modal_adicioanl").text(respuesta["mascotaAdicional"]);
      $('#modalDetalleMascota .modal_img img').attr('src', serverurl + respuesta["mascotaFoto"]);
      $('#modalDetalleMascota #modal_perfil').attr('href', serverurl + "perfilMascota/" + id_masc);
    }
  });

});

/* ==================================================================
*   PERFIL CLIENTE- modal detalle factura
*/

$("#results_factura").on("click", "li", function () {

  var urlajax = $(".panel_historial_factura").attr("urlajax");
  var idVenta = $(this).data('nfactura');
  console.log("clic ajax id_venta data : " + idVenta);

  $.ajax({
    url: urlajax,
    method: "POST",
    data: {
      "id_Venta": idVenta
    },
    success: function (respuesta) {
      // console.log(respuesta);
      $("#lista_detalle_venta").html(respuesta);
    }
  });

});

/* ==================================================================
 *   LISTA FACTURA - modal detalle factura
 */

$(".table").on("click", ".btn-detalle-modal", function () {
  var cod_factura = $(this).attr("id_fact");
  var serverurl = $(this).attr("serverurl");

  var urlajax = serverurl + "ajax/clienteAjax.php";
  console.log("clic ajax id_fact : " + cod_factura);
  console.log("server ajax : " + urlajax);

  $.ajax({
    url: urlajax,
    method: "POST",
    data: {
      "id_Venta": cod_factura
    },
    success: function (respuesta) {
      // console.log(respuesta);
      $("#lista_detalle_venta").html(respuesta);
    }
  });


});
/*=========================================================
 * GENERA FACTURA: tabla lista factura factura click
 */
$(".table").on("click", ".btn-factura-imp", function () {
  var cod_factura = $(this).attr("id_fact");
  var cliente = $(this).attr("dni_cli");
  generarFacturaPDF(cliente, cod_factura);

});

/*=========================================================
* GENERA TICKET: tabla lista factura,ticket click
*/
$(".table").on("click", ".btn-ticket-imp", function () {
  var cod_factura = $(this).attr("id_fact");
  var cliente = $(this).attr("dni_cli");
  generarTicketPDF(cliente, cod_factura);

});


/*=========================================================
* GENERAR HISTORIA : del perfil de la mascota historia
*/
$(".tab-content").on("click", ".btn-historia-imp", function () {
 
  var cod_historia = $(this).attr("id_hist");
  generarHistoriaPDF(cod_historia);


});


function generarFacturaPDF(cliente, factura) {
  var serverurl = $('.btn-detalle-modal').attr("serverurl");
  var ancho = 700;
  var alto = 800;
  //calcular posicion x, y para centrar la ventana
  var x = parseInt((window.screen.width / 2) - (ancho / 2));
  var y = parseInt((window.screen.height / 2) - (alto / 2));

  $url = serverurl + 'factura/generaFactura.php?cl=' + cliente + '&f=' + factura;
  window.open($url, "Factura", "left=" + x + ",top=" + y + ",height=" + alto + ",width=" + ancho + ",scrollbar=si,location=no,resizable=si,menubar=no");
}
function generarTicketPDF(cliente, factura) {
  var serverurl = $('.btn-detalle-modal').attr("serverurl");
  var ancho = 600;
  var alto = 800;
  //calcular posicion x, y para centrar la ventana
  var x = parseInt((window.screen.width / 2) - (ancho / 2));
  var y = parseInt((window.screen.height / 2) - (alto / 2));

  $url = serverurl + 'factura/generaTicket.php?cl=' + cliente + '&f=' + factura;
  window.open($url, "Ticket", "left=" + x + ",top=" + y + ",height=" + alto + ",width=" + ancho + ",scrollbar=si,location=no,resizable=si,menubar=no");
}
/*==========X=========GENERA FACTURA========X==================*/


/*==========X=========Generar historia=======X==================*/
function generarHistoriaPDF(historia) {
  var serverurl = $('.btn-historia-imp').attr("serverurl");
  var ancho = 700;
  var alto = 800;
  //calcular posicion x, y para centrar la ventana
  var x = parseInt((window.screen.width / 2) - (ancho / 2));
  var y = parseInt((window.screen.height / 2) - (alto / 2));


  $url = serverurl + 'factura/generaHistoria.php?nh=' + historia ;
  window.open($url, "Historia", "left=" + x + ",top=" + y + ",height=" + alto + ",width=" + ancho + ",scrollbar=si,location=no,resizable=si,menubar=no");
}

