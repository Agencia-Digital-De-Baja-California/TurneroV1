<?php

	//funcion encriptar con md5
	function encriptarMd5($contraseña){
		
		$encriptado=md5($contraseña);
	
		return $encriptado;
	
	}

	//consulta
	function consulta($con, $sql, $error){
	
		$resultado=mysqli_query($con,$sql) or die (mysqli_error($con));
	
		return $resultado;
	
	}

	//multiconsulta
	function multi_consulta($con, $sql, $error){
	
		//$resultado = mysqli_multi_query($con, $sql) or die (mysqli_error($con));
		$resultado = false;
		/* ejecutar multi consulta */
		if (mysqli_multi_query($con, $sql)) {
		  
		    do {
		  
		        /* almacenar primer juego de resultados */
		        /*if ($result = mysqli_store_result($con)) {
		  
		            /*while ($row = mysqli_fetch_row($result)) {
		  
		                printf("%s\n", $row[0]);
		  
		            }

		  			
		            mysqli_free_result($result);
		  
		        }*/
		  
				  $resultado = true;

		        /* mostrar divisor */
		        /*if (mysqli_more_results($con)) {
		  
		            //printf("-----------------\n");
		  
		        }*/
		  
		    } while (mysqli_next_result($con));
		
		}

		return $resultado;
	
	}

	function limpiar($con,$valor){
	
		$filtro=htmlspecialchars($valor);
	
		$filtro=mysqli_real_escape_string($con,$filtro);
	
		return $filtro;
	
	}

	function convertir_fecha($fecha){
	
		$dato=explode(' ',$fecha);
	
		if($dato[0]=='0000-00-00'){	
	
			echo"Sin fecha";
	
		}else{
	
			$dato=explode('-',$dato[0]);
	
			$meses=array('enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
	
			$mes='';
	
			$mesCovertido=str_replace('0','',$dato[1]);
	
			echo $dato[2]." de ".$meses[$mesCovertido-1]." del ".$dato[0];
	
		}
	
	}

	function verificar_datos($datos){
	
		$valor=true;
	
		for($i=0;$i<count($datos);$i++){
	
			if(!isset($datos[$i]) || empty($datos[$i]) || $datos[$i]=='ninguno'){
	
				 $valor=false;
	
				break;
	
			}
	
		}
	
		return $valor;
	
	}

	function imagen($con,$name,$tmp_name,$size,$type){
	
		if($name!=''){
	
			//imagen del articulo
			$status=false;
			$nombreFoto=limpiar($con,$name);//nombre de la foto
			$ruta=limpiar($con,$tmp_name);//directorio temporal de la imagen
			$tamaño=limpiar($con,$size);//tamaño de la imagen
			$tipo=limpiar($con,$type);//tipo de la imagen
		
			$destino="img/".$nombreFoto;//ruta donde se guardara la imagen
			$imagen="img/".$nombreFoto;//ruta que se guardara en la base de datos
			$mensaje='';
			
			if($tamaño<=1000000){
	
				if($tipo=="image/jpg" or $tipo=="image/gif" or $tipo=="image/jpeg" or $tipo=="image/png"){
	
					copy($ruta,$destino);
					$status=true;
	
				}else{
	
					$mensaje="<span class='mensaje'>Las imagenes del tipo ".$tipo."no son aceptadas</span>";
	
				}//verificacion de tipo 
	
			}else{
	
				$mensaje="<span class='mensaje'>El tamaño de la imagen exede al permitido</span>";
	
			}//verificacion de tamaño
	
		}else{
	
			$imagen='';
	
		}
		
		$resultado=array('status'=>$status,'mensaje'=>$mensaje,'imagen'=>$imagen);
	
		$resultado=json_encode($resultado);
	
		return $resultado;
	
	}

?>
