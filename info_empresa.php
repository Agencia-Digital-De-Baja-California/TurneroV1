<!doctype html>
<html>

	<head>
	
		<meta charset="utf-8">
	
		<title>Informacion de empresa</title>
    
        <link rel="stylesheet" type="text/css" href="css/generales.css">
        <link rel="stylesheet" type="text/css" href="css/info_empresa.css">
		
		<script src="js/funcionesGenerales.js"></script>
		<script src="js/info_empresa.js"></script>
    
    </head>

	<body>

    	<div class="contenedor-principal">        	

        	<div class="contenedor-info" id="info-empresa">

            	<h1>Informacion de la empresa</h1>

                <?php
					
					require_once('funciones/conexion.php');
					require_once('funciones/funciones.php');
					
					$mensaje="";
					
					//editar la informacion
					if(isset($_POST['editar'])){
					
						$datos = array($_POST['nombre'],$_POST['direccion'],$_POST['telefono'],$_POST['correo']);
					
						if(verificar_datos($datos)){
							
							$nombre = limpiar($con,$_POST['nombre']);
							$direccion = limpiar($con,$_POST['direccion']);
							$telefono = limpiar($con,$_POST['telefono']);
							$correo = limpiar($con,$_POST['correo']);
							$fecha = date('Y-m-d H:i:s');
							$status = true;
							
							if($_FILES['logo']['name'] != ''){
								$name = $_FILES['logo']['name'];
								$tmp_name = $_FILES['logo']['tmp_name'];
								$size = $_FILES['logo']['size'];
								$type = $_FILES['logo']['type'];
								$mensajes = imagen($con,$name,$tmp_name,$size,$type);
								$mensajes = json_decode($mensajes);
								$status = $mensajes -> status;
								$mensaje = $mensajes -> mensaje;
								$logo = "logo='$mensajes->imagen',";
							}else{

								$logo = '';
							
							}
							
							if($status == true){
								
								$sql = "update info_empresa set $logo nombre='$nombre',direccion='$direccion', telefono='$telefono',correo='$correo',fecha_actualizacion='$fecha'";
								$error = "Error actualizar la informacion de la empresa";
								$editar = consulta($con,$sql,$error);
								
								if($editar == true){
								
									$mensaje = "<div class='correcto'>Datos actualizados correctamente</div>";
								
								}else{
								
									$mensaje = "<div class='mensaje'>Error al actualizar la informacion</div>";
								
								}
							}

						}else{
							
							$mensaje = "<div class='mensaje'>Hay campos vacios</div>";
						
						}//si essite editar
					
					}
					
					//seleccionar la informacion de la empresa
					$sql = "select * from info_empresa";
					$error = "Error al cargar los datos de la empresa";
					$buscar = consulta($con,$sql,$error);
					
					$info = mysqli_fetch_assoc($buscar);
					$idEmpresa = $info['id'];
					$logo = $info['logo'];
					$nombre = $info['nombre'];
					$direccion = $info['direccion'];
					$telefono = $info['telefono'];
					$correo = $info['correo'];
					$fecha = $info['fecha_actualizacion'];
				
				?>
            	
            	<ul class="info-empresa">
                	
                	<li class="li li-1">
                    	<strong class="strong strong-logo">Logo:</strong>
                        <figure class="logoImagen">
							<img src="<?php echo $logo;?>" alt="Logotipo" title="Logotipo">
                        </figure>
					</li>
                
                	<li class="li"><strong class="strong">Nombre:</strong><span class="span"><?php echo $nombre;?></span></li>
                    <li class="li"><strong class="strong">Direccion:</strong><span class="span"><?php echo $direccion;?></span></li>
                    <li class="li"><strong class="strong">Telefono:</strong><span class="span"><?php echo $telefono;?></span></li>
                    <li class="li"><strong class="strong">Correo:</strong><span class="span"><?php echo $correo;?></span></li>
                    <li class="li"><strong class="strong">Fecha:</strong><span class="span"><?php echo $fecha;?></span></li>
                
                </ul>
                
                <button id="editarInfo" value="<?php echo $idEmpresa;?>">Editar</button>
           		 
           		<div class="mensajes"><?php echo $mensaje;?></div>
            
            </div><!--contenedor-->
            
            <form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="formLogin" id="form-editar-info" class="form-editar" enctype="multipart/form-data">

                    <h1>Editar informacion de la empresa</h1>
            
                    <div class="contenedor-controles">
            
                        <figure class="logoImagen"
><img src="<?php echo $logo;?>" alt="Logotipo" title="Logotipo"></figure>
                        <label class="label">Logo:</label><input type="file" name="logo" id="logo" placeholder="Imagen de logo" class="input">
                        <label class="label">Nombre:</label><input type="text" name="nombre" id="nombre" value="<?php echo $nombre;?>" class="input">
                        <label class="label">Direccion:</label><input type="text" name="direccion" id="direccion"value="<?php echo $direccion;?>" class="input">
                        <label class="label">Telefono:</label><input type="tel" name="telefono" id="telefono" value="<?php echo $telefono;?>" class="input">
                        <label class="label">Correo:</label><input type="email" name="correo" id="correo" value="<?php echo $correo;?>" class="input">
                    	<div class="clear"></div>	
                    
                    </div>
    
                    <input type="submit" name="editar" id="editar" value="Actualizar informacion">
    
                   	<button class="cerrar" id="cerrarEditarInfo">Cerrar</button>
            
                </form>
        	
        	<a href="index.php" class="link-menu">Menu Principal</a>
        
        </div><!--contenedor principal-->
	
	</body>

</html>
