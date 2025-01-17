  // variables  mostrar notas de mascotas
	var busy = false;
	var limit = 3;
	var offset = 0;
	var recarga="";

    /* ================================================
    *   MOSTAR NOTAS DE  MASCOTAS
    ================================================ */
	  function displayRecords(lim, off) {
      	var url = $('.Formulario_ajax_simple').attr("action");

      	var codmascota = $("#nota_codmascota").val();
        // console.log("COD MASCOTA:  "+codmascota);
        // console.log("offset en record: "+off);

        $.ajax({
          type: "POST",
          url: url,
          data: "limit=" + lim + "&offset=" + off + "&codmascota=" + codmascota ,
          cache: false,
          beforeSend: function() {
            $("#loader_message").html("").hide();
            $('#loader_image').show();
          },
          success: function(html) {
            // console.log(html);
            if(recarga=="SI"){
	          $("#results").html(html);
	          if (html == "") {
                $("#loader_message").html('<button class="btn btn-default" type="button">Sin resultados buscar</button>').show();
                
                $("#loader_image").hide();
              }else{
                $("#loader_image").show();
              }

            }else{
            	$("#results").append(html);
		          if (html == "") {
		            $("#loader_message").html('<button class="btn btn-default btn-sm" type="button">No mas resultados</button>').show();
		            $("#loader_image").hide();
		          }	
            }
	          
          }
        });
	 }
  /* =======================================================
  *    MOSTRAR MAS NOTAS AL SER CLICK EN BOTON MOSTRAR MAS
  ========================================================== */
	  $(document).on("click", "#loader_image", function(e){
		e.preventDefault();
	    busy = true;
	    offset = limit + offset;
	    recarga="";
	    displayRecords(limit,offset);
	});

  /* ======================================================
  *    Borrar NOTA PERFIL MASCOTA
  ========================================================= */
  $(document).on("click", "#btn_del_nota", function(e){
    e.preventDefault();
    // console.log("clic eliminar nota");
    var nota_id_dele = $(this).attr("value");
    var urll = $(this).attr("server");

    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Eliminar nota de mascota',
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if(result.value){  
        $.ajax({
          url: urll,
          type: "post",
          data: {
            nota_id_dele:nota_id_dele
          },
          dataType:"json",
          success: function(data){
            // console.log(data);
            alerta_simple_toastr(data);          
          }
        }); 
      }
    });

  });

   /* ======================================================
  *    EDITAR NOTA PERFIL MASCOTA
  ========================================================= */
  $(document).on("click", "#btn_edit_nota", function(e){
    e.preventDefault();
    console.log("clic edita nota");

      var nota_id_edit = $(this).attr("value");
      var urll = $(this).attr("server");
      // alert(del_id);
      $.ajax({
        url: urll,
        type: "post",
        data: {
          nota_id_edit:nota_id_edit
        },
        dataType:"json",
        success: function(data){
          // console.log(data);
          $("#nota_id_edit").val(data["idNota"]);         
          $("#nota_codmascota_edit").val(data["codMascota"]);         
          $("#nota_descripcion_edit").val(data["notaDescripcion"]);
          // caracteres restantes
          var max_char=140;
          var chars = $('#nota_descripcion_edit').val().length;
          var diff = max_char - chars;
          $('#contador_edit').html(diff);
        }
      }); 
    
  });

  
  /* ---Mostrar Alertas toastr, @param: data: array --- */
  function alerta_simple_toastr(data){
    if(data["Alerta"]==="success"){

      toastr.success(data["Titulo"],data["Texto"]);
      // al eliminar
      if(data["Form"]==="perfil_historial"){
        limit_h = 4;
        offset_h = 0;
        recarga_h="SI";
        RecordsHistorialMascota(limit_h,offset_h);
      }
      // notas de mascota, perfil mascota
      if(data["Form"]==="notas"){
        recarga="SI";
        limit = 3;
        offset = 0;
        displayRecords(limit, offset);
      }
      // recarga despues de borrar, session adjunto historia,perfil mascota
      if(data["Form"]==="recarga_adj"){
        RecordsAdjuntos(data["codH"]);
      }
      // recarga seccion vacuna al eliminar
      if(data["Form"]==="Vacuna"){
        datosVacunaLista(data["IdEsp"]);
      }
      // recarga seccion historial vacuna al eliminar. desde perfil mascota
      if(data["Form"]==="VacunaH"){
        RecordsHistorialVacunaMascota();
      }

    }else if(data["Alerta"]==="warning"){
      toastr.warning(data["Titulo"],data["Texto"]);
    }
  }


/* ============================================================
*     MOATRAR MAS HISTORIAL MASCOTAS- pag. perfil mascota
=============================================================== */
var busy_h = false;
var limit_h = 4;
var offset_h = 0;
var recarga_h="";

 $(document).on("click", "#btn_mashistoria", function(e){
    e.preventDefault();
      // $("#loader_message").html("").hide();
      busy_h = true;
      offset_h = limit_h + offset_h;
      recarga_h="";
      RecordsHistorialMascota(limit_h,offset_h);
  });
 /* Mostrar listado de historial medico de mascota
 * @param: lim, off. desde hasta para paginacion
 */
 function RecordsHistorialMascota(lim, off) {
    var url = $('.panel_historial_mascota').attr("urlajax");
    
    var codmascota = $("#nota_codmascota").val();
    
    // console.log("COD MASCOTA:  "+codmascota);
    // console.log("offset en record: "+off);

    $.ajax({
      type: "POST",
      url: url,
      data: "limit=" + lim + "&offset=" + off + "&codmascota=" + codmascota ,
      cache: false,
      dataType:"json",
      beforeSend: function() {
        $("#loader_mesg").html("").hide();
        $('#btn_mashistoria').show();
      },
      success: function(html) {
        // console.log(html);
        // recargar SI. aplicar .html() , si no aplica el append()
        if(recarga_h=="SI"){
        $("#results_historial").html(html["ListaH"]);
        $(".total-hist").text(html["TotalH"]);
        start_image_popUp();
        if (html["ListaH"] == "") {
            $("#loader_mesg").html('<button class="btn btn-default" type="button">Sin resultados buscar</button>').show();
            
            $("#btn_mashistoria").hide();
          }else{
            $("#btn_mashistoria").show();
          }

        }else{
          $("#results_historial").append(html["ListaH"]);

          $(".total-hist").text(html["TotalH"]);
          start_image_popUp();
          if (html["ListaH"] == "") {
            $("#loader_mesg").html('<button class="btn btn-default btn-sm" type="button">No mas resultados</button>').show();
            $("#btn_mashistoria").hide();
          } 
        }
        
      }
    });
  }

  /* ======================================================
  *    Borrar historia completa PERFIL MASCOTA
  ========================================================= */
  $(document).on("click", "#btn_del_historia", function(e){
    e.preventDefault();
    // console.log("clic eliminar historia");
    var historia_id_dele = $(this).attr("value");
    
    var urll = $('.panel_historial_mascota').attr("urlajax");
    
    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Los datos serán eliminados completamente del sistema',
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if(result.value){
        
        $.ajax({
          url: urll,
          type: "post",
          data: {
            historia_id_dele:historia_id_dele
          },
          dataType:"json",
          success: function(data){
            // console.log(data);
            alerta_simple_toastr(data);          
          }
        });
      }
    });

  });

  /* ======================================================
  *    EDITAR HISTORIA -modal- PERFIL MASCOTA
  ========================================================= */
  $(document).on("click", "#btn_edit_historia", function(e){
    e.preventDefault();
    // console.log("clic edita historia");

      var hist_id_edit = $(this).attr("value");
      var urlajax = $('.panel_historial_mascota').attr("urlajax");
      
      $.ajax({
        url: urlajax,
        type: "post",
        data: {
          hist_id_edit:hist_id_edit
        },
        dataType:"json",
        success: function(data){
              // console.log(data);
              $("#historia_id_edit").val(data["codHistorialM"]);         
              $("#historia_motivo").val(data["histMotivo"]);   
              $("#historia_anamnesis").val(data["histAnamnesis"]);         
              $("#historia_sintomas").val(data["histSintomas"]);
              $("#historia_diagnostico").val(data["histDiagnostico"]);
              $("#historia_diagnosticopre").val(data["histDiagnosticoPre"]);
              $("#historia_tratamiento").val(data["histTratamiento"]);
             
              $("#historia_fecha").val(data["histFecha"]);

              // formatear hora
              let horaArray = data["histHora"].split(":");
              let horas = horaArray[0];
              let minutos = horaArray[1];
              let ampm = horas >= 12 ? 'PM' : 'AM';

              // Convertir de formato de 24 horas a formato de 12 horas
              horas = horas % 12;
              horas = horas ? horas : 12; // La hora '0' se muestra como '12'

              // Agregar cero inicial si los minutos son menores a 10
              minutos = minutos < 10 ? '0' + minutos : minutos;

              let horaFormateada = horas + ':' + minutos + ' ' + ampm;
              $("#historia_hora").val(horaFormateada);
    
        }
      }); 
    
  });


  /* ======================================================
  *    Borrar un(1) archivo adjunto, desde PERFIL MASCOTA
  ========================================================= */
  $(document).on("click", "#btn_del_adjuntos", function(e){
    e.preventDefault();
    // console.log("clic eliminar adjuntos");
    var adjunto_id_dele = $(this).attr("value");
    var urll = $('.panel_historial_mascota').attr("urlajax");
 
    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Eliminar archivo adjunto',
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if(result.value){
       $.ajax({
        url: urll,
        type: "post",
        data: {
          adjunto_id_dele:adjunto_id_dele
        },
        dataType:"json",
        success: function(data){
          // console.log(data);
          alerta_simple_toastr(data);          
        }
      }); 
      }
    });
    
  });
/*>>>>>>>>>>>>>>>> SESIONES DE HISTORIAS >>>>>>>>>>>>>>>>>>>>*/
  
  /* recargar seccion adjunto de historia img y pdf
  *  @param: codH: codigo de historia a recargar
  */
  function RecordsAdjuntos(codH) {
    var url = $('.panel_historial_mascota').attr("urlajax");

    $.ajax({
      type: "POST",
      url: url,
      data: "adj_codhistorial=" + codH ,
      cache: false,
      dataType:"json",
      beforeSend: function() {
        // console.log("Cargando...");
      },
      success: function(html) {
        // console.log(html);
        $("#"+codH+" .archivo-adj").html(html["Images"]);
        $("#"+codH+" .archivo-adj-pdf").html(html["PDF"]);
        start_image_popUp();
        // mostrar algun error
        alerta_simple_toastr(html);
      }
    });
  }

  /* recargar seccion INF GENERAL de historia
  *  @param: codH: codigo de historia a recargar
  */
  function RecordsInfHistoria(codH) {
    var url = $('.panel_historial_mascota').attr("urlajax");
    
    $.ajax({
      type: "POST",
      url: url,
      data: "inf_codhistorial=" + codH ,
      cache: false,
      dataType:"json",
      beforeSend: function() {
        // console.log("Cargando...")
      },
      success: function(html) {
        // console.log(html);
        $("#"+codH+" .sectioninfh").html(html["InfoH"]);
        $("a."+codH+"").text(html["Motivo"]);
                
      }
    });
  }

/*>>>>>>>-X->>>>>>> SESIONES DE HISTORIAS >>>>>>-X->>>>>>>>>*/

  /* ==================================================================
  * colocar en input hidden cod de historia en formulario agregar mas archivos adj.
  ================================================================== */
  $('#modal-add-adjuntoh').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var recipient = button.data('codhistoria'); // Extract info from data-* attributes
    var modal = $(this);
   
    modal.find('.modal-body input[name="adjunto_codhistoria_up"]').val(recipient);
  });

  /*==================================================================*/
  /* historial mascota Start Magnific Pop Up (imagenes adjuntas)
  /*==================================================================*/
  function start_image_popUp(){
    $('.archivo-adj').each(function(){
      $(this).magnificPopup({
        delegate:'a',
        type: 'image',
        gallery:{
          enabled: true
        }
      });
    });  
  }

/*==================================================================*/
/*  ADD VACUNA- EDITAR VACUNA PAGE LISTA DE VACUNA
/*==================================================================*/    
  // ocultar form editar vacuna
  $(".form-vacuna-edit").hide(800);
  // cancelar edicion de vacuna
   $(".btn-cancel-edit").click(function(){
    $("#collapse-"+$(this).val()+" .form-vacuna-edit").hide(800);
    $("#collapse-"+$(this).val()+" .form-vacuna-add").show("slow");
  });
  
  /*---EDITAR VACUNA: buscar datos a editar*/
  $(document).on("click", ".btn-editar-v", function(e){
    e.preventDefault();
    // console.log("clic edita VACUNA ");

      var vacuna_id_edit = $(this).attr("idvacuna");
      var urll = $(".FormularioAjax").attr("action");
      // console.log("id vacuna: "+vacuna_id_edit);
      // console.log("server en form: "+urll);

      $.ajax({
        url: urll,
        type: "POST",
        data: {
          vacuna_id_edit:vacuna_id_edit
        },
        dataType:"json",
        success: function(data){
          // console.log(data);
          // mostrar form edit, ocultar form add vacuna
          $("#collapse-"+data["especieId"]+" .form-vacuna-edit").show("slow");
          $("#collapse-"+data["especieId"]+" .form-vacuna-add").hide(800);
          // llenar form editar
          $("#collapse-"+data["especieId"]+" .form-vacuna-edit input[name='vacuna_idvacuna_up']").val(data["idVacuna"]);         
          $("#collapse-"+data["especieId"]+" .form-vacuna-edit input[name='vacuna_nombre_up']").val(data["vacunaNombre"]);         
          $('#collapse-'+data["especieId"]+' .form-vacuna-edit input[name="vacuna_especie_up"]').val(data["especieId"]);         
          
        },
        error: function(error) {
          console.log(error);
        }
      }); 
    
  });

  /*- recarga seccion lista de vacuna segun especie
  *   @param: idespecielista: id de especie a recargar
  */
  function datosVacunaLista(idespecielista){

    var urll = $(".FormularioAjax").attr("action");
    // console.log("server url: "+urll);
    // console.log("id especie buscar recarga: "+idespecielista);
    $.ajax({
      url: urll,
      type: "POST",
      data: {
        idespecielista:idespecielista
      },
      success: function(html){
        // console.log(html);
        $('#collapse-'+idespecielista+' .tags').html(html);
        // ocultar form editar
        $("#collapse-"+idespecielista+" .form-vacuna-edit").hide(800);
        $("#collapse-"+idespecielista+" .form-vacuna-add").show("slow");
        document.querySelector("#collapse-"+idespecielista+" .FormularioAjax").reset();
      },
      error: function(error) {
        console.log(error);
      }
    });
  }

  /*--ELIMINAR VACUNA: eliminar al hacer clic en btn eliminar*/
  $(document).on("click", ".btn-delete-v", function(e){
    e.preventDefault();
    // console.log("clic eliminar vacuna");
    var vacuna_id_del = $(this).attr("value");
    var urll = $(".FormularioAjax").attr("action");
    // console.log("serve: "+urll);

    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Eliminar Vacuna',
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if(result.value){  
        $.ajax({
          url: urll,
          type: "POST",
          data: {
            vacuna_id_del:vacuna_id_del
          },
          dataType:"json",
          success: function(data){
            // console.log(data);
            alerta_simple_toastr(data);          
          }
        }); 
      }
    });

  });

/*================================================*/
/* HISTORIAL VACUNA. PERFIL MASCOTA: mostrar historial de vacunas de mascota y total
/*===============================================*/
function RecordsHistorialVacunaMascota() {
    var url = $('.panel_vacuna_historial_mascota').attr("urlajax");
    
    var codmascota = $("#nota_codmascota").val();    
    // console.log("COD MASCOTA:  "+codmascota);
    $.ajax({
      type: "POST",
      url: url,
      data: "codmascota=" + codmascota,
      cache: false,
      dataType:"json",
      success: function(html) {
        // console.log(html);
        $("#results_historial_vacuna").html(html["ListaV"]);
        $(".total-hist-vacuna").text(html["TotalV"]);
        
      }
    });
  }

/* EDITAR HISTORIAL VACUNA mostrar datos en modal
*/
  $(document).on("click", "#btn_edit_historia_vacuna", function(e){
    e.preventDefault();
    // console.log("clic edita vacuna historia");

      var histv_id_edit = $(this).attr("value");
      var urlajax = $('.panel_vacuna_historial_mascota').attr("urlajax");
      
      $.ajax({
        url: urlajax,
        type: "POST",
        data: {
          histv_id_edit:histv_id_edit
        },
        dataType:"json",
        success: function(data){
          // console.log(data);

          $("#vacuna_idhistoria_up").val(data["idHistoriaVacuna"]);         
          $("#historia_vacuna_idvacuna_up").val(data["idVacuna"]);    
          $("#historia_prox_fecha_vacuna_up").val(data["historiavFechaProxVacuna"]);
          $("#historia_vacuna_producto_up").val(data["historiavProducto"]);
          $("#historia_vacuna_observacion_up").val(data["historiavObser"]);
          $('.selectpicker').selectpicker('refresh');
        }
      }); 
    
  });

  /* Eliminar historial vacuna
  */
  $(document).on("click", "#btn_del_historia_vacuna", function(e){
    e.preventDefault();
    // console.log("clic eliminar historia");
    var historiav_id_dele = $(this).attr("value");
    
    var urlajax = $('.panel_vacuna_historial_mascota').attr("urlajax");
      
    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Los datos serán eliminados completamente del sistema',
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if(result.value){
        
        $.ajax({
          url: urlajax,
          type: "POST",
          data: {
            historiav_id_dele:historiav_id_dele
          },
          dataType:"json",
          success: function(data){
            // console.log(data);
            alerta_simple_toastr(data);          
          }
        });
      }
    });

  });



/*==================================================================*/
/* PERFIL CLIENTE: al iniciar , cargar lista HISTORIAL FACTURADO
/*==================================================================*/
var busy_c = false;
var limit_c = 2;
var offset_c = 0;
var recarga_c="";

 $(document).on("click", "#btn_masfactura", function(e){
    e.preventDefault();
      busy_c = true;
      offset_c = limit_c + offset_c;
      recarga_c=""; // append
      RecordsHistorialFactura(limit_c,offset_c);
  });

 function RecordsHistorialFactura(lim, off) {
    var url = $('.panel_historial_factura').attr("urlajax");
    // clienteAjax.php
    // codigo de cliente ----
    var dnicliente = $('input[name="dni_cliente_perfil"]').val();
    var busquedafecha = $('input[name="fecha_buscar_perfilc"]').val();
    if(busquedafecha==""){
      busquedafecha="";
    }
    // console.log("COD MASCOTA:  "+codmascota);
    // console.log("offset en record: "+off);
    // console.log("buscar fecha facturado: "+busquedafecha);
    
    $.ajax({
      type: "POST",
      url: url,
      data: {
        "limit": lim,
        "offset": off,
        "dnicliente": dnicliente,
        "busquedafecha": busquedafecha
      },
      cache: false,
      dataType:"json",
      beforeSend: function() {
        $("#loader_mesg").html("").hide();
        $('#btn_masfactura').show();
      },
      success: function(html) {
        // console.log(html);
        if(recarga_c=="SI"){
        $("#results_factura").html(html["ListaF"]);
        $(".total-factura").text(html["TotalF"]);
        
        if (html["ListaF"] == "") {
            $("#loader_mesg").html('<button class="btn btn-default" type="button">Sin resultados buscar</button>').show();
            
            $("#btn_masfactura").hide();
          
          }else{
            $("#btn_masfactura").show();
          }

        }else{
          $("#results_factura").append(html["ListaF"]);

          $(".total-factura").text(html["TotalF"]);
          
          if (html["ListaF"] == "") {
            $("#loader_mesg").html('<button class="btn btn-default btn-sm" type="button">No mas resultados</button>').show();
            $("#btn_masfactura").hide();
          } 
        }
        
      }
    });
  }

  $(document).on("click", "#btn_buscarfecha", function(e){
    e.preventDefault();
      
      busy_c = true;
      limit_c = 2;
      offset_c = 0;
      recarga_c="SI"; // append o html, aqui es html
      RecordsHistorialFactura(limit_c,offset_c);
});
 
/*==================================================================*/
/* PERFILES: al iniciar , cargar lista de notas, historial medico de mascota
*   y historial factura cliente
/*==================================================================*/

$(document).ready(function() {
    // start to load the first set of data
    if (busy == false || busy_h==false) {
      
      busy = true;
      busy_h=true;
      // start to load the first set of data
      var codm = $("#nota_codmascota").val();
      if(codm === void 0){
        // console.log("NO PERFIL MASCOTA");
      }else{
        // historial de notas
        displayRecords(limit, offset);
        // historial de vacunas
        RecordsHistorialVacunaMascota();
      }
      RecordsHistorialMascota(limit_h,offset_h);
      // PERFIL CLIENTE HISTORIAL FACTURADO
      RecordsHistorialFactura(limit_c,offset_c);
    }
  });