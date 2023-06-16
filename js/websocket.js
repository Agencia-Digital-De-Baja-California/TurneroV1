
agregarEvento(window, 'load', iniciarWebsocket, false);

var imgStatus = null;

var tono = null;

function iniciarWebsocket(){

	imgStatus = document.getElementById('imgStatus');

	socket = new WebSocket("ws://172.31.181.216:8888/php/proyectos/turnero/turnero/server.php");

	socket.addEventListener('open', abierto, false);
	socket.addEventListener('message', recibido, false);
	socket.addEventListener('close', cerrado, false);
	socket.addEventListener('error', errores, false);

	tono = document.getElementById('tono');

}

//se activa cuando se conecta el cliente a el socket
function abierto(){

	if(imgStatus != null){

		imgStatus.src = "img/conectado.png";

	}

}

//funcion que recibe los emnsajes del socket
function recibido(e){

	var jsonData = JSON.parse(e.data);//decodificar el objeto json

	var turno = document.getElementById('verTurno');
	var caja = document.getElementById('verCaja');

	//si turno biene en 000 o undefined siginfica que no hay nuevos turnos
	if(typeof jsonData.type === 'string' && jsonData.type === 'data'){

		if(typeof jsonData.turno === 'string' && 
		   typeof jsonData.idCaja === 'string'){
		
			if(turno != null && caja != null){

				if(jsonData != '' && jsonData.idCaja != '' && jsonData.status === 'success'){

					turno.innerHTML = jsonData.turno;
					caja.innerHTML = jsonData.idCaja;
		
					mostrarTurnos(jsonData.turno, jsonData.idCaja);

				}
		
			}

		}else{

			console.error('El tipo de dato de turno o caja no es valido');

		}

	}

}

function cerrado(){

	if(imgStatus != null){

		imgStatus.src="img/desconectado.png";	
	
	}
	

}

function errores(){

	if(imgStatus != null){
		
		imgStatus.src="img/error.png";
			
	}
	

}

var tr = "";

var turnsTable = [];

let newArray = [];

//mostrar los turnos que se atienden 
function mostrarTurnos(noTurno = '', noCaja = ''){

	let turn = [];//array que almacenara los turnos a mostrar

	let displayedTurns = load_diplayed_turns();

	//colocar el turno que se va a atender en el array
	turn = {'turno':noTurno,
	        'caja': noCaja};


	//verificar si ya se tienen turnos en pantalla cuando se carga el visualizador de turnos

	if(displayedTurns.length > 0 && newArray.length === 0){

		//si hay turnos en pantalla se entra aqui

		for(let i = 0; i < displayedTurns.length; i++){

			//generacion de array con los turnos en patalla
			if(i === 0){

				newArray[i] = turn;			

			}else{

				newArray[i] = displayedTurns[i-1];

			}

		}

		generate_table(newArray);

	}else{

		//si no hay turnos en pantalla se entra aqui

		newArray.unshift(turn);

		if(newArray.length > 10){

			newArray.pop();

		}

		generate_table(newArray);

	}

}

//cargar turnos que se ya se estan mostrando en pantalla
function load_diplayed_turns(){

	let turns = document.getElementById('turnos').value;

	turns = turns.split('|tr|');

	let arrayTable = [];
	let arrayTurn = []

	for(let i = 0; i < turns.length - 1; i++){

			arrayTurn = turns[i].split('|');

			arrayTable[i] = {'turno':arrayTurn[0], 
						     'caja':arrayTurn[1]};

	}

	return arrayTable;

}

//generar la tabla con los turnos
function generate_table(table = null){

	var th = "<tr><th>Turno</th><th colspan='2'>Caja</th></tr>";
	
	for(var i = 0; i < table.length; i++){	
	
		if(i == 0){
	
			tr = "<tr><td><span  class='primer-fila'>"+table[i]['turno']+"</span></td><td class='td-caja'><span class='caja primer-fila'>Caja</span></td><td class='no-caja'><span  class='primer-fila'>"+table[i]['caja']+"</span></td></tr>".toString();
	
		}else{
	
			tr = tr+"<tr><td>"+table[i]['turno']+"</td><td class='td-caja'><span class='caja'>Caja</span></td><td class='no-caja'>"+table[i]['caja']+"</td></tr>".toString();
	
		}
	
	}
	
	display_table(th + tr);

}

//mostrar la tabla de turnos en pantalla
function display_table(table = ''){

	var tablaTurnos = document.getElementById('tabla-turnos');
	
	tablaTurnos.innerHTML = table;//imprimir los turnos que han pasado y el turno que esta siendo atendido 
	
	tono.play();

}
