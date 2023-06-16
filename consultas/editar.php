<?php
	if(isset($_POST['editar'])){
		require_once('../funciones/conexion.php');
		require_once('../funciones/funciones.php');
		$respuesta=[];
		switch($_POST['editar']){
			case'turnoAtendido':
				$sql="select id from turnos";
				$error="Error al registrar el turno";
				$buscar=consulta($con,$sql,$error);
				$noTurno=mysqli_num_rows($buscar);
				if($noTurno>0){
					$sql="select turno from turnos order by id desc";
					$error="Error al seleccionar el turno";
					$buscar=consulta($con,$sql,$error);
					$resultado=mysqli_fetch_assoc($buscar);			
					$turno=$resultado['turno']+1;
					
					if($turno >= 10 && $turno < 100){
						$turno="0".$turno;
					}else if($turno >= 100){
						$turno;	
					}else{
						$turno="0"."0".$turno;
					}
				}else{
					$turno="00"."1";
				}
					
				$fecha=date("Y-m-d H:i:s");
				$sql="insert into turnos (turno,fechaRegistro) values ('$turno','$fecha')";
				$error="Error al registrar el turno";
				$registrar=consulta($con,$sql,$error);
				
				if($registrar==true){
					$respuesta=array('mensaje'=>'1','turno'=>$turno);		
				}else{
					$respuesta=array('mensaje'=>'0','turno'=>000);	
				}
			break;
			default:
			break;
		}
		//echo $resultado['turno'];
		echo json_encode($respuesta);
	}else{
		echo"<span>Opcion no valida</span>";
	}
?>