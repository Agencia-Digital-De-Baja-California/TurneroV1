agregarEvento(window, 'load', iniciar, false);

function iniciar(){

	var atender = document.getElementById('atender');

	agregarEvento(atender,'click',detectarAccion,false);

}

var jsonFormat = '';

function detectarAccion(e){
	
	var id = "";
	
	if(e){
	
		e.preventDefault();
	
		id = e.target.id;
	
	}
	
	switch(id){
	
		case'atender':	
	
			var ocupado1 = document.getElementById('ocupado').value;//se usa para saber si se esta atendiendo o no un turno
			var idCaja = document.getElementById('idCaja').value;
			var turno = document.getElementById('noTurno').value;
	
			funcion = procesarAtencion;
	
			fichero = 'consultas/registrar.php';
	
			var datos = 'registrar=atencion'+'&ocupado='+encodeURIComponent(ocupado)+'&idCaja='+encodeURIComponent(idCaja)+'&turno='+encodeURIComponent(turno);
	
		break;
	
	}

	conectarViaPost(funcion, fichero, datos);

}

function procesarAtencion(){

	if(conexion.readyState == 4){

		var data = conexion.responseText;
		
		//enviar los datos recibidos mediante ajax en formato json  al socket
		socket.send(data);	
		
		var jsonData = JSON.parse(data);//decodificar los datos en formato json

		var turno = document.getElementById('turno');//turno que se muestra en la pantalla
		var noTurno = document.getElementById('noTurno');//control input noTurno

		turno.innerHTML = jsonData.turno;
		noTurno.value = jsonData.turno;			
			
		var mensajes = document.getElementById('mensajes');

		if(jsonData.status == 'error' || jsonData.status == 'mensaje'){			
			
			//poner mensajes de error o de aviso
			mensajes.innerHTML=jsonData.mensaje;
		
		}else{
		
			mensajes.innerHTML='';
		
		}
	
	}

}