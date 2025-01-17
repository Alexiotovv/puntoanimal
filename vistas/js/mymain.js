(function($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });
  
  // CALENDAR datetimepicker INPUT FECHA TIME HORA
  // FECHA
  $('#id_fecha').datepicker({
      format: "dd-mm-yyyy",
      language: 'es',
      todayHighlight: true,
  });

  $('#id_fecha2').datepicker({
    //format: "yyyy-mm-dd",
    format: "dd-mm-yyyy",
    language: 'es',
    todayHighlight: true,
  });

  // fechas de inicio y final
  $('.fechas').datepicker({
      format: "dd-mm-yyyy",
      language: 'es',
      todayHighlight: true,
  });

  // fecha section facturado perfil cliente
  $('#fecha_perfil_cli').datepicker({
      format: "yyyy-mm-dd",
      language: 'es'
  });

  // HORA
  $('#id_hora').mdtimepicker({
    format: 'h:mm tt',
    theme: 'yellow',
    readOnly: false,
    hourPadding: false
  });

  $('#historia_hora_reg').mdtimepicker({
    format: 'h:mm tt',
    theme: 'yellow',
    readOnly: false,
    hourPadding: false
  }); 

  $('#historia_hora').mdtimepicker({
    format: 'h:mm tt',
    theme: 'yellow',
    readOnly: false,
    hourPadding: false
  }); 
 
  /*==================================================================*/
  /*  ADD MASCOTA, ADD CITA 
  /*==================================================================*/
  
  // cargar img de due���o al buscar en select 
  $(document).on('change','.mascota-dueno .selectpicker',function(){
   var text = $("select[name=mascota-dueno]").val();
    // console.log("select change mascota-dueno: "+text);
   
    var elegido2 = $('select[name=mascota-dueno] option:selected').data('foto');
    $('#imgpreve-cliente-m').attr('src', elegido2);
   
  });

   // cargar img de mascota al buscar en select -> ADD CITA
  $(document).on('change','#cita-select-mascota .selectpicker',function(){
   var text = $("select[name=cita_paciente_reg]").val();
    // console.log("select change cita_paciente_reg: "+text);
   
    var elegido2 = $('select[name=cita_paciente_reg] option:selected').data('foto');
    $('#imgpreve-mascota-cita').attr('src', elegido2);
   
  });
  
    // cargar img de veterinario al buscar en select -> ADD CITA
    $(document).on('change', '#cita-select-mascota .selectpicker', function () {
      var text = $("select[name=cita_paciente_reg]").val();
      // console.log("select change cita_paciente_reg: "+text);
  
      var elegido2 = $('select[name=cita_paciente_reg] option:selected').data('foto');
      $('#imgpreve-mascota-cita').attr('src', elegido2);
  
    });
  /*==================================================================*/
  /*  PERFIL MASCOTA
  /*==================================================================*/
   
  /*------  nota maximo de caracteres en textareA ---------*/
  var max_char=140;
  $('.max').html(max_char);

  $('#descrip-nueva-nota').attr("maxlength",max_char);
  $('#descrip-nueva-nota').keyup(function(){
    var chars = $(this).val().length;
    var diff = max_char - chars;
    $('#contador').html(diff);
    if(diff<=0){
      $('#descrip-nueva-nota').attr("maxlength",max_char);
    }
  });

  $('#nota_descripcion_edit').attr("maxlength",max_char);
  $('#nota_descripcion_edit').keyup(function(){
    var chars = $(this).val().length;
    var diff = max_char - chars;
    $('#contador_edit').html(diff);
    if(diff<=0){
      $('#nota_descripcion_edit').attr("maxlength",max_char);
    }
  });
  // NUEVA NOTA MOSTRAR Y CANCELAR
  $("#form-nota-nueva").hide(800);
  // cancelar
   $("#btn-cancelar-nota").click(function(){
    $("#form-nota-nueva").hide(800);
    $("#descrip-nueva-nota").val("");
    $("#contador").html("");
  });
   // mostrar
  $("#btn-nueva-nota").click(function(){
    $("#form-nota-nueva").show("slow");
  });


  /*==================================================================*/
  /*  IMAGEN PREVE- INPUT FILE, mostrar imagen previa de imagen a cargar
  /*==================================================================*/
   function readImage(input){
    if(input.files && input.files[0]){

      var reader = new FileReader();
      reader.onload = function(e){
        $('#imgpreve').attr('src', e.target.result); //renderizamos la imagen
      }
      reader.readAsDataURL(input.files[0]);
    }else{
      console.log("esta vacio input file foto");
    }
  }
  /*
  *  Validar Formato de imagen a cargar, Aceptar PNG,JPG,JPEG
  */
  function validar_formato_img(foto){
    var ext = foto.val().split('.').pop().toLowerCase();
      if($.inArray(ext, ['png','jpg','jpeg']) == -1) {
        $(".error_msg").text("Imagen NO valida...");
        // limpiar campo
        var ruta_file_foto_clear = foto.val("");
      }else{
        readImage(foto[0]);
        $(".error_msg").text("");
      }
   }
     
   // imagen preve logo empresa
    var btnUploadEm = $("#archivo_foto_subir_empresa");
   btnUploadEm.change(function(){
      validar_formato_img(btnUploadEm);
    });

    // preve-imagen-subir mascota
    var btnUploadMa = $("#archivo_foto_subir_mascota");
    btnUploadMa.change(function(){
      validar_formato_img(btnUploadMa);
    });

   // imagen preve add usuario
  var btnUploadAd = $("#archivo_foto_subir_admin");
   btnUploadAd.change(function(){
      validar_formato_img(btnUploadAd);
    });

  /*==================================================================*/
  /*  ADD CLIENTE GUARDAR
  /*==================================================================*/
  // limpiar radios avatar
  function reset_radio_avatar(){
    $('input[name="avatar-cliente"]').prop('checked',false);
      var ruta_avatar_va = $("#form-add-Cliente input[name='avatar-cliente']:radio").is(':checked');
      // console.log("avatares en false: "+ruta_avatar_va);  
    }

  /** codigo a ejecutar cuando se detecta un cambio de archivo input file
  *  
  */
  var btnUploadCliente = $("#archivo_foto_subir");
   btnUploadCliente.change(function(){
    // avalar radio checked
    var avatar_select = $("#form-add-Cliente input[name='avatar-cliente']:radio").is(':checked');
    // si es true
    if(avatar_select){
      // console.log("avatar en true: "+avatar_select);
      reset_radio_avatar();
      validar_formato_img(btnUploadCliente);            
    }else{
      validar_formato_img(btnUploadCliente);
      // console.log(avatar_select);
    }
      
    });

  /* Click al seleccionar un avartar para cliente
  * 
  */
  $("input[name=avatar-cliente]").click(function(){
    var ruta_avatar_va = $("#form-add-Cliente input[name='avatar-cliente']:radio").is(':checked');
    // AVATAR SELECCIONADO
    if(ruta_avatar_va){
      // console.log(ruta_avatar_va);
      // limpiar campo file foto
      var ruta_file_foto_clear = $('input:file[name=archivo_foto_subir]').val("");
      // console.log("ruta file limpiar updat: "+ruta_file_foto_clear);
      $(".error_msg").text("");

      var ruta_avatar_value = $('input:radio[name=avatar-cliente]:checked').val();
      // console.log("seleccionado avatar value : "+ruta_avatar_value);
      var ruta_avatar_db = $("input:radio[name=avatar-cliente]:checked").data('fotocliente');
      
      // console.log("seleccionado avatar :"+ruta_avatar_db);
      
      $('#imgpreve').attr('src',ruta_avatar_db); //renderizamos la imagen
      
    }else{
      // console.log("avatar NO select");
    }

  });

  function reset_radio_avatar_vet(){
    $('input[name="avatar-veterinario"]').prop('checked',false);
      var ruta_avatar_va = $("#form-add-Veterinario input[name='avatar-veterinario']:radio").is(':checked');
      // console.log("avatares en false: "+ruta_avatar_va);  
  }

  var btnUploadVeterinario = $("#archivo_foto_subir");
  btnUploadVeterinario.change(function(){
    // avalar radio checked
    var avatar_select = $("#form-add-Veterinario input[name='avatar-veterinario']:radio").is(':checked');
    // si es true
    if(avatar_select){
      // console.log("avatar en true: "+avatar_select);
      reset_radio_avatar_vet();
      validar_formato_img(btnUploadVeterinario);            
    }else{
      validar_formato_img(btnUploadVeterinario);
      // console.log(avatar_select);
    }
      
    });

  $("input[name=avatar-veterinario]").click(function () {

    var ruta_avatar_va = $("#form-add-Veterinario input[name='avatar-veterinario']:radio").is(':checked');
    console.log(ruta_avatar_va)
    // AVATAR SELECCIONADO
    if (ruta_avatar_va) {
      // console.log(ruta_avatar_va);
      // limpiar campo file foto
      var ruta_file_foto_clear = $('input:file[name=archivo_foto_subir]').val("");
      // console.log("ruta file limpiar updat: "+ruta_file_foto_clear);
      $(".error_msg").text("");

      var ruta_avatar_value = $('input:radio[name=avatar-veterinario]:checked').val();
      // console.log("seleccionado avatar value : "+ruta_avatar_value);
      var ruta_avatar_db = $("input:radio[name=avatar-veterinario]:checked").data('fotoveterinario');

      // console.log("seleccionado avatar :"+ruta_avatar_db);

      $('#imgpreve').attr('src', ruta_avatar_db); //renderizamos la imagen

    } else {
      // console.log("avatar NO select");
    }

  });

 /*==================================================================*/
  /*  ADD mascota GUARDAR
  /*==================================================================*/
  // limpiar radios avatar
  function reset_radio_avatar(){
    $('input[name="avatar-mascota"]').prop('checked',false);
      var ruta_avatar_va = $("#form-add-Mascota input[name='avatar-mascota']:radio").is(':checked');
      // console.log("avatares en false: "+ruta_avatar_va);  
    }

  /** codigo a ejecutar cuando se detecta un cambio de archivo input file
  *  
  */
  var btnUploadMascota = $("#archivo_foto_subir");
   btnUploadMascota.change(function(){
    // avalar radio checked
    var avatar_select = $("#form-add-Mascota input[name='avatar-mascota']:radio").is(':checked');
    // si es true
    if(avatar_select){
      // console.log("avatar en true: "+avatar_select);
      reset_radio_avatar();
      validar_formato_img(btnUploadMascota);            
    }else{
      validar_formato_img(btnUploadMascota);
      // console.log(avatar_select);
    }
      
    });

  /* Click al seleccionar un avartar para mascota
  * 
  */
  $("input[name=avatar-mascota]").click(function(){
    var ruta_avatar_va = $("#form-add-Mascota input[name='avatar-mascota']:radio").is(':checked');
    // AVATAR SELECCIONADO
    if(ruta_avatar_va){
       console.log(ruta_avatar_va);
      // limpiar campo file foto
      var ruta_file_foto_clear = $('input:file[name=archivo_foto_subir]').val("");
      // console.log("ruta file limpiar updat: "+ruta_file_foto_clear);
      $(".error_msg").text("");

      var ruta_avatar_value = $('input:radio[name=avatar-mascota]:checked').val();
      // console.log("seleccionado avatar value : "+ruta_avatar_value);
      var ruta_avatar_db = $("input:radio[name=avatar-mascota]:checked").data('fotomascota');
      
      // console.log("seleccionado avatar :"+ruta_avatar_db);
      
      $('#imgpreve').attr('src',ruta_avatar_db); //renderizamos la imagen
      
    }else{
      // console.log("avatar NO select");
    }

  });


  /*==================================================================*/
  /*  NUEVA VENTA FACTURAR
  /*==================================================================*/
  // cargar img de due���o al buscar en select 
  $(document).on('change','#venta-cliente-db .selectpicker',function(){
   var text = $("select[name=venta-cliente]").val();
   
   if(text==""){
    console.log("sin resultados en select");
   }else{
    var elegido2 = $('select[name=venta-cliente] option:selected').data('foto');
    $('#imgpreve-cliente').attr('src', elegido2);
    // console.log("option change elegido foto: "+elegido2);

   }
  });
  // venta cantidad table
  $(".venta-cantidad").inputSpinner();

  $('.venta-cantidad').keypress(function(e){
    if(isNaN(this.value + String.fromCharCode(e.charCode)))
      return false;
  })
  .on("cut copy paste",function(e){
    e.preventDefault();
  });


   /*==================================================================*/
  /*  ADD PRODUCTO/SERVICIO FORMATO PRECIO ADD producto,servicio
  /*==================================================================*/
   $(document).on('change','#prodservi_tipo',function(){
     var tipo = $(this).val();
     
     if(tipo=="Producto"){
      // console.log("producto");
      $("#inventario-stock").prop("disabled", false);
      $("#inventario_vencimiento").prop("disabled", false);
     }else if(tipo=="Servicio"){
      // console.log("servicio");
      $("#inventario-stock").val("1");
      $("#inventario-stock").prop("disabled", true);
      $("#inventario_vencimiento").prop("disabled", true);
     }
    });

   $("#prodservi_tipo").each(function(){
    var tipo = $(this).val();
     
     if(tipo=="Producto"){
      // console.log("producto");
      $("#inventario-stock").prop("disabled", false);
      $("#inventario_vencimiento").prop("disabled", false);
     }else if(tipo=="Servicio"){
      // console.log("servicio");
      $("#inventario-stock").val("1");
      $("#inventario-stock").prop("disabled", true);
      $("#inventario_vencimiento").prop("disabled", true);
     }
    });
   
 /* ===== ===== SNIPERR STOCK ===== ==================================*/
    $(".input-sniperr").inputSpinner();

    $('.input-sniperr').keypress(function(e){
      if(isNaN(this.value + String.fromCharCode(e.charCode)))
        return false;
    })
    .on("cut copy paste",function(e){
      e.preventDefault();
    });

 /*==================================================================*/
 /*  CAMPO VACIO validar add historia medica 
 /*==================================================================*/
   $(document).on('click','#btn-historia-vacio',function(){
     var motivo = $('#historia_motivo_reg').val();
     var sintomas = $('#historia_sintomas_reg').val();
     var diagnostico = $('#historia_diagnostico_reg').val();
     var tratamiento = $('#historia_tratamiento_reg').val();
     if(motivo.length<1){
     	toastr.warning("Campo vacio","Debe ingresar motivo de consulta");
     }
     if(sintomas.length<1){
     	toastr.warning("Campo vacio","Debe ingresar los sintomas");
     }
     if(diagnostico<1){
     	toastr.warning("Campo vacio","Debe ingresar el diagnostico");
     }
     if(tratamiento<1){
     	toastr.warning("Campo vacio","Debe ingresar el tratamiento");
     }
    });
  // Scroll to top button appear
  // $(document).on('scroll', function() {
  //   var scrollDistance = $(this).scrollTop();
  //   if (scrollDistance > 100) {
  //     $('.scroll-to-top').fadeIn();
  //   } else {
  //     $('.scroll-to-top').fadeOut();
  //   }
  // });

  // Smooth scrolling using jQuery easing
  // $(document).on('click', 'a.scroll-to-top', function(e) {
  //   var $anchor = $(this);
  //   $('html, body').stop().animate({
  //     scrollTop: ($($anchor.attr('href')).offset().top)
  //   }, 1000, 'easeInOutExpo');
  //   e.preventDefault();
  // });

  // inicializar fecha de historia a la fecha actual
  let fechaActual = new Date();
  let fechaFormateada = fechaActual.toISOString().slice(0,10);

  /*Formato YYYY-MM-DD*/
  // Separar la fecha en partes (año, mes, día)
  var partesFecha = fechaFormateada.split("-");
  // Reorganizar las partes en el formato DD-MM-YYYY
  var fechaFormateada2 = partesFecha[2] + "-" + partesFecha[1] + "-" + partesFecha[0];
  /**/

  $("#historia_fecha_reg").val(fechaFormateada2);
  $("#inventario_vencimiento").val(fechaFormateada2);

  // inicializar hora de historia a la hora actual
  let horas = fechaActual.getHours();
  let minutos = fechaActual.getMinutes();
  let ampm = horas >= 12 ? 'PM' : 'AM';

  // Convertir de formato de 24 horas a formato de 12 horas
  horas = horas % 12;
  horas = horas ? horas : 12; // La hora '0' se muestra como '12'

  // Agregar cero inicial si los minutos son menores a 10
  minutos = minutos < 10 ? '0' + minutos : minutos;

  let horaFormateada = horas + ':' + minutos + ' ' + ampm;
  $("#historia_hora_reg").val(horaFormateada);
 

})(jQuery); // End of use strict
