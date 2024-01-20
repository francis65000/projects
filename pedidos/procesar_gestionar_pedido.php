<?php

session_start();
	if(!isset($_SESSION["usuario"])){
		header("Location:index.php");
	};
	
	//variables para actualizar datos
	$id 	= "";
	$fecha 	= "";
	$estado = 0;

	$respuesta = "";
	$hay_errores = "";
	$errores = false;

	/////declaracion de variables y lectura de datos//////////
	$host='localhost';
	$user='root';
	$pass='';

	if (isset($_GET['id']) && isset($_GET['fecha'])) {
        $id 	= $_GET['id'];
		$fecha 	= $_GET['fecha'];
    }

    //comprobar si viene por post
	if($_SERVER["REQUEST_METHOD"]=="POST"){
		//comprobar que los campos no esten vacios
		if(!empty($_POST["id"])){
            $id = $_POST["id"];
        }else{
            $hay_errores = "-El campo ID está vacío<br>";
            $errores = true;
        }

		if(!empty($_POST["fecha"])){
            $fecha = $_POST["fecha"];
        }else{
            $hay_errores .= "-El campo fecha está vacío<br>";
            $errores = true;
        }

        $estado = $_POST["estado"];

		if($errores == false){
			try {
				$pdo = new PDO("mysql:host=$host;dbname=pedidos;charset=utf8",$user,$pass);
				$sentencia="UPDATE pedidos SET codped='".$id."', fecha='".$fecha."', enviado='".$estado."' WHERE codped=".$id;
				$stm=$pdo->prepare($sentencia);
				
				$stm->execute();
				$stm->setFetchMode(PDO::FETCH_BOTH);
				//verificar que la consulta se ha realizado correctamente
				if($stm){
					$respuesta = 'Pedido Actualizado Correctamente';
					header ('Location: gestionar_pedido.php');
				}else{
					$hay_errores .= 'Error al Actualizar Pedido';
					$errores = true;
				}
			} catch (Exception $e) {
				$respuesta = "Error: " . $e->getMessage();
			}
		}
	}
    ///////////////////conexion a base de datos///////////////////////////
	try{
        // Verificar si se ha enviado el valor desde gestionar_pedido.php
		$pdo = new PDO("mysql:host=$host;dbname=pedidos;charset=utf8",$user,$pass);
		$sentencia="SELECT * FROM pedidos WHERE codped = $id";
		$stm=$pdo->prepare($sentencia);
		
		$stm->execute();
        $stm->setFetchMode(PDO::FETCH_BOTH);
		

	}catch(PDOException $e){

		echo $e->getMessage();
	};


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Pedido</title>
</head>
<body>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Gestionar Pedido</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner">

							<!-- Header -->
								<header id="header">
									<h2>Gestionar Pedido</h2>
                                    
								</header>

							<!-- Banner -->
								<section id="formulario">
                                <p>Seleccione un pedido para actualizar su estado</p>
                                <div class="content">
                                        <!--AQUI EMPIEZA EL FORMULARIO-->
                                        <form action="procesar_gestionar_pedido.php" method="POST">
                                        <h2>Actualizar datos de Categoría</h2>
                                            <div class="row gtr-uniform">
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="codigo">ID Pedido</label>
                                                    <input type="text" name="id" id="actualizar" value="<?php echo $id; ?>" readonly>
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="nombre">Fecha</label>
                                                    <input type="text" name="fecha" id="actualizar" value="<?php echo $fecha; ?>" readonly>
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="descripcion">Estado</label>
                                                    <select name="estado" id="estado">
                                                        <option name="estado" value="0">Sin Enviar</option>
                                                        <option name="estado" value="1">Enviado</option>
                                                    </select>
                                                </div>
                                                <!-- Break -->
                                                <div class="col-12">
                                                    <ul class="actions">
                                                        <li><input type="submit" value="Actualizar Datos" class="primary"/></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </form>
                                        <!--FIN DEL FORMNULARIO-->
										<?php
											if($errores == false){
												echo $respuesta;
												echo ('<br><a href="gestionar_pedido.php" class="primary">Volver</a>');
												
											}else{
												echo ("Se han producido los siguientes errores:<br>");
												echo $hay_errores;
												echo ('<br><a href="gestionar_pedido.php" class="primary">Volver</a>');
											}
										?>
                                    </div>
							</section>
						</div>
					</div>

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
							<!-- Menu -->
								<nav id="menu">
								<?php
									if($_SESSION["perfil"]=="restaurante"){

										include "../includes/menu_restaurante.php";

									}elseif($_SESSION["perfil"]=="administrador"){

										include "../includes/menu_admin.php";
									};
										
								?>
								</nav>

							<!-- Footer -->
								<footer id="footer">
									<p class="copyright">&copy; Gestión de pedidos Web <br>Módulo Desarrollo Web en Entorno Servidor <br>Curso 23/24 </p>
								</footer>

						</div>
					</div>

			</div>

		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>

	</body>
</html>
</body>
</html>