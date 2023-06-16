<?php

	if(isset($_POST['registrar'])){

		require_once('../funciones/conexion.php');
		require_once('../funciones/funciones.php');

		$respuesta=[];

		switch($_POST['registrar']){
			case'reset-turnos':
				
				$fecha=date("Y-m-d H:i:s");
				$turno="000";
				
				$sql = "DELETE FROM turnos;";
				$sql .= "ALTER TABLE turnos AUTO_INCREMENT=0";
				$error = "Error al resetear turno";

				$resetTurn = multi_consulta($con, $sql, $error);
				
				$sql = "DELETE FROM atencion;";
				$sql .= "ALTER TABLE atencion AUTO_INCREMENT=0";
				$error = "Error al resetear atencion";
				
				$resetAtention = multi_consulta($con, $sql, $error);
				
				if($resetTurn == true && $resetAtention == true){
				
					$respuesta = array('status'=>'correcto','mensaje'=>'Turnos reseteados correctamente','turno'=>$turno);		
				
				}else{
				
					$respuesta = array('status'=>'error','mensaje'=>'Error al resetear turnos','turno'=>000);	
				
				}

			break;
			case'turno':
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
					$respuesta=array('status'=>'correcto','mensaje'=>'Turno registrado','turno'=>$turno);		
				}else{
					$respuesta=array('status'=>'error','mensaje'=>'Error al registrar el turno','turno'=>000);	
				}
			break;
			case'atencion':

				$idCaja=limpiar($con,$_POST['idCaja']);
			
				$registrar=false;
			
				$editar=false;
			
				$turno='000';
			
				$error="";
			
				$status="";
			
				$mensaje="";
			
				$ocupado="";
				
				//funcion para dar un nuevo turno a la caja
				function darTurno($con,$idCaja){
			
					$turno='000';
			
					$sql="select id,turno from turnos where atendido='0' order by id asc";
					$error="Error al seleccionar el turno";
			
					$buscar=consulta($con,$sql,$error);
			
					$noResultados=mysqli_num_rows($buscar);
					
					//verificar si hay turnos disponibles
					if($noResultados > 0){
			
						$resultado=mysqli_fetch_assoc($buscar);			
						$fecha=date("Y-m-d H:i:s");
						$turno=limpiar($con,$resultado['turno']);
						$idUsuario=1;//$_SESSION['idUsuario'];
						
						//poner el turno en la tabla de atenciones
						$sql="insert into atencion (turno,idCaja,idUsuario,fechaAtencion) values ('$turno','$idCaja','$idUsuario','$fecha')";
						$error="Error al registrar el turno en atencion";
						$registrar=consulta($con,$sql,$error);
					
						//poner en la tabla turnos que caja lo esta atendiendo
						$sql="update turnos set atendido='$idCaja' where turno='$turno'";
						$error="Error al poner la caja que atiende el turno";
						$editar=consulta($con,$sql,$error);
						
						if($registrar==true && $editar==true){

							$status="success";
							$mensaje="Turno registrado";
							$ocupado=true;
						
						}else{
						
							$status="error";
							$mensaje="Error al dar los turnos".$error;
							$ocupado=false;
						
						}

					}else{
						
						$status="mensaje";
						$mensaje="No hay turnos disponibles";	
						$ocupado=false;
					
					}
					
					return array('turno' => $turno, 'status' => $status, 'mensaje' => $mensaje, 'ocupado' => $ocupado);
				
				}
				
				//funcion para consultar los turnos en la tabla atencion que no ha sido atendidos
				function turnosEnAtencion($con,$idCaja){
					//seleccionar los turnos en la tabla atencion que correspondan a la caja y que estan en o en la columna atendido
					$sqlTurnosAtencion="select id,turno from atencion where atendido='0' and idCaja='$idCaja'";

					$error="Error al seleccionar el turno en atencion ";
					
					return $buscarTurnosAtencion=consulta($con,$sqlTurnosAtencion,$error);	
				
				}
				
				//funcion para actualizar las atenciones de turnos
				function actualizarAtencion($con,$idCaja,$turno){
				
					$sql="update atencion set atendido='1' where turno='$turno' and idCaja='$idCaja'";
				
					$error="Error al actualizar  el turno en atencion";
				
					$editar=consulta($con,$sql,$error);
				
				}
				
				//consultar los turnos en atencion
				$turnosAtencion=turnosEnAtencion($con,$idCaja);
				$noTurnosAtencion=mysqli_num_rows($turnosAtencion);
				
				if($noTurnosAtencion==0){

					//dar un nuevo turno si no existen turnos sin atender 
					$resultado=darTurno($con, $idCaja);
					
					$turno=$resultado['turno'];
					$ocupado=$resultado['ocupado'];
					$status=$resultado['status'];
					$mensaje=$resultado['mensaje'];	
				
				}else if($noTurnosAtencion==1){
					
					//si solamente hay un turno por atender se actualiza ela t}atencion y se da uno nuevo
					
					if($_POST['turno']!='000'){
					
						$turno=limpiar($con,$_POST['turno']);
					
					}else{
					
						$resultado=mysqli_fetch_assoc($turnosAtencion);
					
						$turno=$resultado['turno'];	
					
					}
				
					actualizarAtencion($con,$idCaja,$turno);
				
					$resultado=darTurno($con,$idCaja);
				
					$turno=$resultado['turno'];
				
					$ocupado=$resultado['ocupado'];
				
					$status=$resultado['status'];
				
					$mensaje=$resultado['mensaje'];	
					
				}else if($noTurnosAtencion>1){
				
					//si hay mas de un turno se actualiza la atencion del turno que estaba siendo atendido y se envia el siguiente 
					$turno=limpiar($con,$_POST['turno']);
				
					actualizarAtencion($con, $idCaja, $turno);
					
					$turnosAtencion=turnosEnAtencion($con,$idCaja);

	 				$resultado=mysqli_fetch_assoc($turnosAtencion);	

					$turno=$resultado['turno'];
					
					$ocupado=true;
					$status="mensaje";
					$mensaje="Existen turnos por atender";
					
				}else{
				
					$status="error";					
				
					$mensaje="Error en la veririfaccion de turnos en atencion";
				
					$ocupado=false;
				
				}//veriricar que no haya mas turnos en atencion
								
				$respuesta=array('status' => $status,
					             'mensaje' => $mensaje,
					             'turno' => $turno, 
					             'ocupado' => $ocupado,
					             'idCaja' => $idCaja);		
			break;

			default:
				
				$respuesta=array('status' => 'error', 
					             'mensaje' => 'Peticion desconocida', 
					             'turno' => '000', 
					             'opcuado' => false, 
					             'idCaja' => '0');																	

			break;
		
		}
		
		echo json_encode($respuesta);

	}else{

		echo"<span>Opcion no valida</span>";

	}
?>