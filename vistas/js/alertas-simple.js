const formularios_ajax_simple = document.querySelectorAll(".Formulario_ajax_simple");

function enviar_formulario_ajax_simple(e){
	e.preventDefault();

	let form=$(this);

 	console.log(form);
	
	let data = new FormData(this);
	let method = this.getAttribute("method");
	let action = this.getAttribute("action");

	let encabezados = new Headers();

	let config = {
		method: method,
		headers: encabezados,
		mode: 'cors',
		cache: 'no-cache',
		body: data
	}

	fetch(action,config)
		.then(respuesta => respuesta.json())
		.catch(error => console.error('Error: ',error))
		.then(respuesta => {
			// console.log(respuesta);
			return alertas_ajax_simple(respuesta);
	});
}

formularios_ajax_simple.forEach(formularios => {
	formularios.addEventListener("submit", enviar_formulario_ajax_simple);
});

function alertas_ajax_simple(alerta){
	// console.log(alerta.Alerta);
	if(alerta.Alerta==="success"){
	 toastr.success(alerta.Titulo,alerta.Texto);
	 if(alerta.Form==="Nota"){
	 	document.querySelector(".Formulario_ajax_simple").reset();
	 	$("#form-nota-nueva").hide(800);
	    $("#contador").html("");
	 	recarga="SI";
	 	limit = 3;
    	offset = 0;
    	// lista de notas de mascota
    	displayRecords(limit, offset);
	 }
	 if(alerta.Form==="Nota-edit"){
	 	document.querySelector(".Formulario_ajax_simple").reset();
	 	recarga="SI";
	 	limit = 3;
    	offset = 0;
    	displayRecords(limit, offset);
    	$('.bd-example-modal-sm').modal('toggle');
	 }
	 // Archivos adjuntos input file
	 if(alerta.Form==="recarga_adj"){
	 	document.querySelector("#modal-add-adjuntoh .Formulario_ajax_simple").reset();
    	$('#modal-add-adjuntoh').modal('toggle');
   		RecordsAdjuntos(alerta.codH);
   		recarga_inputfile();
	 }
	 // ------ Historial mascota
	 if(alerta.Form==="recarga_infh"){
	 	document.querySelector("#modal-edit-historia .Formulario_ajax_simple").reset();
    	$('#modal-edit-historia').modal('toggle');
   		RecordsInfHistoria(alerta.codH);
	 }
	 // historial vacunas -> perfil mascota
	 if(alerta.Form==="VacunaH"){
	 	if(alerta.Action==="Editar"){
	 		document.querySelector("#modal-edit-historia-vacuna .Formulario_ajax_simple").reset();
    		$('#modal-edit-historia-vacuna').modal('toggle');
	 	}else{
	 		document.querySelector(".modal-vacuna-add-sm .Formulario_ajax_simple").reset();
    		$('.modal-vacuna-add-sm').modal('toggle');
	 	}
	 	RecordsHistorialVacunaMascota(); // load-more.js()
	 }
	}else if(alerta.Alerta==="error"){
	 toastr.error(alerta.Titulo,alerta.Texto);
	}else if(alerta.Alerta==="warning"){
	 toastr.warning(alerta.Titulo,alerta.Texto);	
	}else if(alerta.Alerta==="redireccionar"){
		window.location.href=alerta.URL;
	}
}

function recarga_inputfile(){
	$("#uploader").html(`
    <div class="row uploadDoc">
      <div class="col-sm-4">
        <div class="docErr">
        <i class="fas fa-exclamation-circle mr-2 fa-sm"></i>
           Por favor subir un archivo valido
        </div><!--error-->
        <div class="fileUpload btn btn-elegir">
          <img class="icon" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNTYgNTYiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDU2IDU2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggc3R5bGU9ImZpbGw6I0U5RTlFMDsiIGQ9Ik0zNi45ODUsMEg3Ljk2M0M3LjE1NSwwLDYuNSwwLjY1NSw2LjUsMS45MjZWNTVjMCwwLjM0NSwwLjY1NSwxLDEuNDYzLDFoNDAuMDc0YzAuODA4LDAsMS40NjMtMC42NTUsMS40NjMtMVYxMi45NzhjMC0wLjY5Ni0wLjA5My0wLjkyLTAuMjU3LTEuMDg1TDM3LjYwNywwLjI1N0MzNy40NDIsMC4wOTMsMzcuMjE4LDAsMzYuOTg1LDB6Ii8+PHBvbHlnb24gc3R5bGU9ImZpbGw6I0Q5RDdDQTsiIHBvaW50cz0iMzcuNSwwLjE1MSAzNy41LDEyIDQ5LjM0OSwxMiAiLz48cGF0aCBzdHlsZT0iZmlsbDojQzhCREI4OyIgZD0iTTQ4LjAzNyw1Nkg3Ljk2M0M3LjE1NSw1Niw2LjUsNTUuMzQ1LDYuNSw1NC41MzdWMzloNDN2MTUuNTM3QzQ5LjUsNTUuMzQ1LDQ4Ljg0NSw1Niw0OC4wMzcsNTZ6Ii8+PGNpcmNsZSBzdHlsZT0iZmlsbDojRkZGRkZGOyIgY3g9IjE4LjUiIGN5PSI0NyIgcj0iMyIvPjxjaXJjbGUgc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGN4PSIyOC41IiBjeT0iNDciIHI9IjMiLz48Y2lyY2xlIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBjeD0iMzguNSIgY3k9IjQ3IiByPSIzIi8+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==" >
          <span class="upl" id="upload">Elegir archivo</span>
          <input type="file" class="upload up" id="up" name="archivos_multiples[]" onchange="readURL(this);" />
        </div>
      </div>
      <div class="col-7">
        <div class="group">
          <input type="text" name="archivos_adjtitulo[]" />
          <span class="highlight"></span>
          <span class="bar"></span>
          <label>Titulo</label>
        </div>
      </div>
      <div class="col-1">
      <a class="btn-check"><i class="fa fa-times"></i></a>
      </div>
      </div>
    `);
}