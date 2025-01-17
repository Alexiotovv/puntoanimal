const formularios_ajax = document.querySelectorAll(".FormularioAjax");

function enviar_formulario_ajax(e){
	e.preventDefault();
 	let form=$(this);

 	// console.log(form);
	
	let data = new FormData(this);
	let method = this.getAttribute("method");
	let action = this.getAttribute("action");
	let tipo = this.getAttribute("data-form");

	let encabezados = new Headers();

	let config = {
		method: method,
		headers: encabezados,
		mode: 'cors',
		cache: 'no-cache',
		body: data
	}

	let texto_alerta;

	if(tipo==="save"){
		texto_alerta="Los datos quedaran guardados en el sistema";
	}else if(tipo==="delete"){
		texto_alerta="Los datos serán eliminados completamente del sistema";
	}else if(tipo==="update"){
		texto_alerta="Los datos del sistema serán actualizados";
	}else if(tipo==="search"){
		texto_alerta="Se eliminará el término de búsqueda y tendrás que escribir uno nuevo";
	}else if(tipo==="loans"){
		texto_alerta="Desea remover los datos seleccionados";
	}else if(tipo==="cita"){
		texto_alerta="Atender esta cita";
	}else{
		texto_alerta="Quieres realizar la operación solicitada";
	}

	Swal.fire({
		title: '¿Estás seguro?',
		text: texto_alerta,
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Aceptar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if(result.value){
			fetch(action,config)
			.then(respuesta => respuesta.json())
			.catch(error => console.error('Error: ',error))
			.then(respuesta => {
				
				//console.log(respuesta);
				
				return alertas_ajax(respuesta);

			});
		}
	});

}

formularios_ajax.forEach(formularios => {
	formularios.addEventListener("submit", enviar_formulario_ajax);
});

function alertas_ajax(alerta){
	
	
	if(alerta.Alerta==="simple"){
		Swal.fire({
			title: alerta.Titulo,
			text: alerta.Texto,
			type: alerta.Tipo,
			confirmButtonText: 'Aceptar'
		});
	}else if(alerta.Alerta==="recargar"){
		Swal.fire({
			title: alerta.Titulo,
			text: alerta.Texto,
			type: alerta.Tipo,
			confirmButtonText: 'Aceptar'
		}).then((result) => {
			if(result.value){
				if(alerta.User=="citaedit"){
					window.location.href=alerta.URL;
				}else if(alerta.User=="vacuna"){
					// recarga seccion vacuna segun especie, load-more.js(4..)
					datosVacunaLista(alerta.IdEsp);
				}else{
					location.reload();
				}

			}
		});
	}else if(alerta.Alerta==="limpiar"){
		Swal.fire({
			title: alerta.Titulo,
			text: alerta.Texto,
			type: alerta.Tipo,
			confirmButtonText: 'Aceptar'
		}).then((result) => {
			if(result.value){
				document.querySelector(".FormularioAjax").reset();
				$('.selectpicker').selectpicker('refresh');
				// console.log(alerta.User);
				var imgreset = document.querySelector(".FormularioAjax .img-preve img");
				if((alerta.User=="cliente") || (alerta.User=="usuario")){
					imgreset.src = alerta.clearFoto+"vistas/images/general/user-foto.svg";
				}
				if(alerta.User=="mascota"){
					imgreset.src = alerta.clearFoto+"vistas/images/general/image-preve.svg";
					// funcion ajaxSearch.js(173)
					recargarRaza("");
					// dueño en select ajaxSearch.js(35)
      				cargarDuenoMascota("");
				}
				if(alerta.User=="cita"){
      				cargarDuenoMascota("");
      				var cli = $("select[name=mascota-dueno]").val();
      				// carga en select ajaxSearch.js(288)
      				cargarMascotaSelect(cli);
				}
				if(alerta.User=="citalista"){
				 window.location.href=alerta.URL;
				}
				if(alerta.User=="historial"){
				 window.location.href=alerta.URL;
				}
			}
		});
	}else if(alerta.Alerta==="toastr"){
		toastr.success(alerta.Titulo,alerta.Texto);
		if(alerta.Redi=="redireccionar"){
			window.location.href=alerta.URL;
		}
	}else if(alerta.Alerta==="redireccionar"){
		window.location.href=alerta.URL;
	}
}
