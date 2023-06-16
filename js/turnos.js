agregarEvento(window, 'load', iniciar, false);

function iniciar(){

	window.onresize = function(){

		console.log(window.innerHeight);
		console.log(window.innerWidth);

	}

	var atender = document.getElementById('atender');

	agregarEvento(atender, 'click', detectarAccion, false);

}

function detectarAccion(e){

	var id = "";
	
	if(e){
	
		e.preventDefault();
	
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

	if(conexion.readyState){

		var jsonData = JSON.parse(conexion.responseText);
		var noTurno = document.getElementById('turno');
	
		noTurno.innerHTML = jsonData.turno;

	}

}

