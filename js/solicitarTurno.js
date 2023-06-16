agregarEvento(window, 'load', iniciar, false);

function iniciar(){

	var solicitar = document.getElementById('solicitarTurno');

	agregarEvento(solicitar, 'click', detectarAccion, false);

}

function detectarAccion(e){

	var id = "";
	
	if(e){
	
		id = e.target.id;
	
	}

	switch(id){
	
		case'solicitarTurno':	
	
			funcion = procesarSolicitud;
			fichero = 'consultas/registrar.php';
			datos = 'registrar=turno';
	
		break;
		default:
	
			console.log('Opcion no reconocida');
	
		break;
	
	}
	
	conectarViaPost(funcion,fichero,datos);

}

function procesarSolicitud(){

	if(conexion.readyState === 4){

		var jsonData = JSON.parse(conexion.responseText);
		var noTurno = document.getElementById('turno');
	 
	 	noTurno.innerHTML = jsonData.turno;

	}

}