<?php

	session_start();

?>

<!doctype html>

<html>

	<head>

		<meta charset="utf-8">

		<title>Registrar usuarios</title>

        <link rel="stylesheet" type="text/css" href="css/generales.css">
        <link rel="stylesheet" type="text/css" href="css/registro_de_usuarios.css">

    </head>
	<body>

    	<div class="contenedor-principal">

        	<?php

				$mensaje = "";

				require_once('funciones/conexion.php');
				require_once('funciones/funciones.php');

        		if(isset($_POST['registrar'])){

					$data = array($_POST['usuario'],$_POST['password'],$_POST['caja']);

					if(verificar_datos($data)){

						$usuario = limpiar($con,$_POST['usuario']);
						$caja = limpiar($con,$_POST['caja']);

						$sql = "select * from usuarios where usuario='$usuario'";
						$error = "Error al logear al usuario";

						$buscar = consulta($con,$sql,$error);
						$no = mysqli_num_rows($buscar);

						if($no == 0){

							$sql = "select idCaja from usuarios where idCaja='$caja'";
							$error = "Error al buscar usuario registrado";

							$buscar = consulta($con,$sql,$error);
							$no = mysqli_num_rows($buscar);

							if($no == 0){

								$password = limpiar($con,$_POST['password']);
								$password = encriptarMd5($password);

								$fecha = date('Y-m-d H:i:s');
								
								$sql = "insert into usuarios (usuario,password,idCaja,fecha_alta) values ('$usuario','$password','$caja','$fecha')";
								$error = "Error al registrar usuario";

								$buscar = consulta($con,$sql,$error);
								
								$sql = "select id from usuarios where usuario='$usuario'";
								$error = "Error al buscar usuario registrado";

								$buscar = consulta($con,$sql,$error);
								$dato = mysqli_fetch_assoc($buscar);
								
								$sql = "update cajas set idUsuario=$dato[id] where id='$caja'";
								$error = "Error al al colocar el usuario en la caja correspondiente";

								$buscar = consulta($con,$sql,$error);
								
								if($buscar){

									$mensaje = "<span class='correcto'>Usuario registrado</span>";

								}else{

									$mensaje = "<span class='error'>Error al registrar al usuario</span>";

								}

							}else{

								$mensaje = "<span class='mensaje'>La caja ya esta en uso</span>";

							}//ver si la caja esta en uso

						}else{

							$mensaje =" <span class='mensaje'>El usuario ya fue registrado</span>";

						}//ver si el usuario ya fue registrado

					}else{

						$mensaje = "<span class='mensaje'>Hay campos vacios</span>";

					}//verificacion de campos vacios

				}else{

					$mensaje = "";

				}

			?>
        		<section>

                	<form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="formLogin" id="formLogin" class="form-login">

                    	<h1>Registro de usuarios</h1>

                        <div class="contenedor-controles">

                        	<label>Usuario:</label><input type="text" name="usuario" id="usuario" placeholder="Ingrese su usuario" autofocus>
                        	<label>Password:</label><input type="password" name="password" id="password" placeholder="Ingrese su password">
                            <label>Caja:</label>

                            <?php

                            	$sql="select * from cajas";
								$error="Error al cargar las cajas";

								$buscar=consulta($con,$sql,$error);

							?>

                            <select name="caja" id="caja">

                            	<option value="ninguno">Selecciona una caja</option>

                                <?php

                                	while($caja=mysqli_fetch_assoc($buscar)){
										echo "<option value='$caja[id]'>$caja[nombre]</option>";
									}
								?>

                            </select>

                    		<input type="submit" name="registrar" id="registrar" value="Registrar">

                    	</div>

                        <span class="mensajes"><?php echo $mensaje;?></span>

                    </form>

                	<a href="index.php" class="link-menu">Menu Principal</a>

                </section>

        </div>

	</body>

</html>
