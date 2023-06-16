<?php

	session_start();

?>

<!doctype html>

<html>

	<head>

		<meta charset="utf-8">

		<title>Registrar cajas</title>

        <link rel="stylesheet" type="text/css" href="css/generales.css">
        <link rel="stylesheet" type="text/css" href="css/registro_de_cajas.css">

    </head>
	<body>

    	<div class="contenedor-principal">

        	<?php

				$mensaje = "";

        		if(isset($_POST['registrar'])){

					require_once('funciones/conexion.php');
					require_once('funciones/funciones.php');

					$data = array($_POST['nombreCaja']);

					if(verificar_datos($data)){

						$caja = limpiar($con,$_POST['nombreCaja']);
						$sql = "select id from cajas where nombre='$caja'";
						$error = "Error al seleccionar las cajas";

						$buscar = consulta($con,$sql,$error);
						$noCajas = mysqli_num_rows($buscar);

						if($noCajas == 0){	

							$sql = "select id from cajas";
							$error = "Error al seleccionar las cajas";

							$buscar=consulta($con,$sql,$error);

							$noCajasPermitidas = 10;

							if(mysqli_num_rows($buscar) < $noCajasPermitidas){
							
								$fecha = date('Y-m-d H:i:s');
								$sql = "insert into cajas (nombre,fecha_de_registro) values ('$caja', '$fecha')";
								$error = "Error al registrar la caja";
							
								$registrar = consulta($con,$sql,$error);
							
								if($registrar == true){
							
									$mensaje = "<span class='correcto'>Caja registrada</span>";
							
								}else{
							
									$mensaje = "<span class='mensaje'>error al registrar la caja</span>";
							
								}
							
							}else{

								$mensaje = "<span class='mensaje'>No se pueden registrar mas cajas</span>";
							
							}
						
						}else{
						
							$mensaje = '<span class="mensaje">La caja ya fue registrada</span>';
						
						}
					
					}else{
					
						$mensaje = "<span class='mensaje'>Hay campos vacios</span>";
					
					}
				
				}
			
			?>
        	
        		<section>
            
	        	<form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="formLogin" id="formLogin" class="form-login">

	            	<h1>Registro de cajas</h1>

	                <div class="contenedor-controles">

	                	<label>Nombre:</label><input type="text" name="nombreCaja" id="nombreCaja" placeholder="Ingrese el nombre de la caja">
	            		<input type="submit" name="registrar" id="registrar" value="Registrar">

	            	</div>

	                <span class="mensajes"><?php echo $mensaje;?></span>

	            </form>
                   
                 <a href="index.php" class="link-menu">Menu Principal</a>
            
            </section>
        
        </div>
	
	</body>

</html>
