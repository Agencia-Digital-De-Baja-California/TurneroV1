agregarEvento(window, 'load', iniciar, false);

var info = '';

var formEditar = '';

function iniciar(){

	info = document.getElementById('info-empresa');//div con informacion de la empresa
	
	var editarInfo = document.getElementById('editarInfo');//boton para abrir el formualrio para editar datos
	
	formEditar = document.getElementById('form-editar-info');//formualrio para editar datos
	
	var cerrar = document.getElementById('cerrarEditarInfo');//boton para cerrar le formulario
	
	agregarEvento(editarInfo, 'click', abrirForm, false);
	agregarEvento(cerrar, 'click', abrirForm, false);

}

function abrirForm(e){

	e.preventDefault();

	id = e.target.id;
	
	var displayInfo = '';
	var displayForm = '';

	switch(id){

		case'editarInfo':

			displayInfo = 'none';
			displayForm = 'block';			

		break;
		case'cerrarEditarInfo':

			displayInfo = 'block';
			displayForm = 'none';		

		break;
		default:

			console.log('Error');

		break;

	}

	info.style.display = displayInfo;

	formEditar.style.display = displayForm;

}
