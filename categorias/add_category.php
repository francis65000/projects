<?php
session_start();
if(!isset($_SESSION["usuario"])){
	header("Location:index.php");
};

//////////////////**GESTION BACK DEL FORMULARIO *////////////////////
if($_SERVER["REQUEST_METHOD"]=="POST"){
////////////////////variablees globales/////////////////////////////
$errores="";
$resultado="";


////////////////////variablees globales/////////////////////////////
$errores="";
$resultado="";
//////////////////**GESTION BACK DEL FORMULARIO *////////////////////


	///////////////////////funciones/////////////////////////

	function limpiaDatos($dato){
		return trim(htmlspecialchars(strip_tags($dato)));
	};

	/////declaracion de variables y lectura de datos//////////

	$nombre=isset($_POST["nombre"]) ? limpiaDatos($_POST["nombre"]) : "";
	$descripcion=isset($_POST["descripcion"]) ? limpiaDatos($_POST["descripcion"]) : "";
	$host='localhost';
	$user='root';
	$pass='';
	
	if(empty($nombre) || empty($descripcion)){
		$errores="No se puede enviar el formulario con campos vacíos.";

	}else{



	///////////////////conexion a base de datos///////////////////////////

	try{
		/////////conexion a base de datos y sentencia////////////////////
		
		$pdo = new PDO("mysql:host=$host;dbname=pedidos;charset=utf8",$user,$pass);
		$sentencia="INSERT into categorias (nombre,descripcion) values (:nombre,:descripcion)";
		$stm=$pdo->prepare($sentencia);
		$stm->bindParam(":nombre",$nombre);
		$stm->bindParam(":descripcion",$descripcion);

		$stm->execute();

		$resultado="El registro se ha insertado con exito,modificaciones: ".$stm->rowCount();

		$stm=null;

	}catch(PDOException $e){

		echo $e->getMessage();
	};

};


};

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add_category</title>
</head>
<body>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Editorial by HTML5 UP</title>
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
									<h2>Añadir Categoria</h2>
								</header>

							<!-- Banner -->
								<section id="formulario">
									<div class="content">
										<form action="add_category.php" method="post">
											<table>
												<tr>
													<td><label for="nombre">Nombre</label></td>
													<td><input type="text" name="nombre" id="nombre"></td>
												</tr>
												<tr>
													
													<td><label for="nombre">Descripcion</label></td>
													<td><input type="text" name="descripcion" id="descripcion"></td>
												</tr>
												<tr>
													<td><input type="submit" name="addcategory"></td>
												</tr>
											</table>

										</form>
										

										<p><?php echo isset($errores) ? $errores : "" ?></p>
										<p><?php echo isset($resultado) ? $resultado : ""  ?></p>

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