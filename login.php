<?php

	session_start();

?>

<!doctype html>

<html>

	<head>

		<meta charset="utf-8">

		<title>Login</title>

        <link rel="stylesheet" type="text/css" href="css/generales.css">
        <link rel="stylesheet" type="text/css" href="css/login.css">

    </head>
	<body>
    	<div class="contenedor-principal">

        	<?php

				$mensaje="";

        		if(isset($_POST['login'])){

					require_once('funciones/conexion.php');
					require_once('funciones/funciones.php');

					$data=array($_POST['usuario'],$_POST['password']);

					if(verificar_datos($data)){

						$usuario=limpiar($con,$_POST['usuario']);
						$password=limpiar($con,$_POST['password']);
						$password=encriptarMd5($password);

						$sql="select * from usuarios where usuario='$usuario' and password='$password'";
						$error="Error al logear al usuario";
						$buscar=consulta($con,$sql,$error);

						$no=mysqli_num_rows($buscar);

						if($no>0){

							$usuario=mysqli_fetch_assoc($buscar);

							$_SESSION['id']=$usuario['id'];
							$_SESSION['usuario']=$usuario['usuario'];
							$_SESSION['password']=$usuario['password'];
							$_SESSION['idCaja']=$usuario['idCaja'];

							header('location:caja.php');

						}else{

							$mensaje="<span class='mensaje'>Usuario o password incorrectos</span>";

						}

					}else{

						$mensaje="<span class='mensaje'>Hay campos vacios</span>";

					}

				}


			?>
        		<section>

                	<form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="formLogin" id="formLogin" class="form-login">
                    	
                    	<h1>Login</h1>
                        
                        <div class="contenedor-controles">
                        
                        	<label>Usuario</label><input type="text" name="usuario" id="usuario" placeholder="Ingrese su usuario">
                        	<label>Password</label><input type="password" name="password" id="password" placeholder="Ingrese su password">
                    		<input type="submit" name="login" id="login" value="Login">
                    	
                    	</div>
                        
                        <span class="mensajes"><?php echo $mensaje;?></span>
                    
                    </form>
                    
                    <a href="index.php" class="link-menu">Menu Principal</a>
                
                </section>
        
        </div>
	
	</body>

</html>
