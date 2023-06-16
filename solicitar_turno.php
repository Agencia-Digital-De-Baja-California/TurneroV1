<!doctype html>

<html>

	<head>

		<meta charset="utf-8">

		<title>Solicitar turno</title>

        <link rel="stylesheet" type="text/css" href="css/generales.css">
        <link rel="stylesheet" type="text/css" href="css/solicitarTurno.css">

    </head>
	<body>

    	<div class="contenedor-principal">

        	<?php

					require_once('funciones/conexion.php');
					require_once('funciones/funciones.php');
					
					//turnos
					$sql = "select turno from turnos order by id desc";
					$error = "Error al seleccionar el turno";

					$buscar = consulta($con,$sql,$error);
					
					$resultado = mysqli_fetch_assoc($buscar);	
					$noResultados = mysqli_num_rows($buscar);
					
					if($noResultados == 0){

						$turno = "000";

					}else{

						$turno = $resultado['turno'];

					}
					
					//datos de la empresa
					$sqlE = "select * from info_empresa";
					$errorE = "Error al cargar datos de la empresa ";

					$buscarE = consulta($con,$sqlE,$errorE);
						
					$info = mysqli_fetch_assoc($buscarE);	

			?>
        	<div class="contenedor-caja">

                <header class="contenedor-logo">

                	<figure class="logo-empresa">
                		<img src="<?php echo $info['logo'];?>">
                	</figure>
            		
            		<h1 class="nombre-empresa"><?php echo $info['nombre'];?> Bienvenido</h1>
                	        
                </header>
                
                <div class="clear"></div>
                
                <span class="datos-turno">Turno: <span id="turno"><?php echo $turno;?></span></span>
                
                <input type="submit" name="solicitarTurno" id="solicitarTurno" value="Solicitar Turno">
            	<input type="hidden" name="turno" id="noTurno" value="">

				<button class="menu-li" onclick="window.location.href='ticket.php'">Imprime ticket</button>

            </div>
        
        </div>
        
        <script src="js/jquery-3.1.0.min.js"></script>
		<script src="js/funcionesGenerales.js"></script>
		<script src="js/solicitarTurno.js"></script>
	
	</body>
	

</html>
