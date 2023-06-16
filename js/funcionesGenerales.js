// JavaScript Document
var conexion = '';

function conectarViaGet(funcion = '', fichero = ''){

	conexion = ajax();
  	conexion.onreadystatechange = funcion;
 	conexion.open('GET', fichero,  true);
  	conexion.send(null);	

}

function conectarViaPost(funcion = '', 
	                     fichero = '', 
	                     datos = ''){

	conexion=ajax();
	conexion.onreadystatechange=funcion;
	conexion.open('POST',fichero,true);
	conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	conexion.send(datos);

}

function cerrarForm(e){

	var id='';
	
	var elemento='';
	
	if(e){
	
		e.preventDefault();
		elemento = e.target;
		id = e.target.id;
	
	}else{
	
		id = window.event.srcElement();
	
	}

	var idForm = '';
	
	switch(id){

		case'cerrarEditarInfo':
		
			idForm='form-editar-info';
		
		break;
		default:
		
			console.log('Error al cerrar');
		
		break;
	
	}
	
	var form=document.getElementById(idForm);
	
	agregarEvento(form,'click',function(){

		form.style.display = 'none';

	}, false);

}

function agregarEvento(elemento = '',
	                   evento = '',
	                   funcion = '',
	                   captura = ''){
	
	if(elemento.addEventListener){

		elemento.addEventListener(evento, funcion, captura);
		return true;

	}else if(elemento.attachEvent){

		elemento.attachEvent(evento, funcion);
		return true;

	}else{

		return false;

	}

}

function ajax(){

	if(window.XMLHttpRequest){
	
		xmlHttp=new XMLHttpRequest();
		return xmlHttp;
	
	}else if(window.ActiveXObject){
	
		xmlHttp=new ActiveXObject('Micrososft.XMLHTTP');
		return xmlHttp;

	}else{

		return false;

	}

}