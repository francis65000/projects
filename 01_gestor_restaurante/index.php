<?php
	session_start();
	$mensaje = "";
	$usuario = "";

	if ($_SERVER["REQUEST_METHOD"] === "POST"){
		if (isset($_SESSION["usuario"])) {
			
			session_destroy();
			$mensaje = "Sesión Cerrada";
		}
	}elseif (isset($_SESSION["usuario"])){
		$usuario = $_SESSION["usuario"];
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Editorial by HTML5 UP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner">

							<!-- Header -->
								<header id="header">
									<h2>GESTIÓN INTEGRAL DE PEDIDOS, GRUPO DE RESTAURANTES.</h2>
								</header>

							<!-- Banner -->
								<section id="banner">
									<div class="content">
										<header>
											<h1>Inicio</h1>
											<p>Login general</p>
										</header>
										<table>
											<form action="./inicio/valida_formulario.php" method="POST">
												<tr>
													<td><label for="perfil">TIPO DE PERFIL</label></td>
													<td><select name="perfil">
														<option value="restaurante">Restaurante</option>
														<option value="administrador">Administrador</option>
													</select></td>
												</tr>
												<tr>
													<td><label for="usuario">USUARIO</label></td>
													<td><input name="usuario" type="text" value="<?php echo $usuario; ?>"></td>
												</tr>
												<tr>
													<td><label for="pass">CONTRASEÑA</label></td>
													<td><input name="pass" type="password"></td>
												</tr>
												<tr>
													<td><input class="button primary" name="enviar" value="acceder" type="submit"></td>
												</tr>
											</form>
											<form action="index.php" method="POST">
												<td><input  name="eliminar" value="eliminar datos" type="submit"></td>
												<td><?php echo $mensaje; ?></td>
											</form>
										</table>
									</div>
									
								</section>
						</div>
					</div>
				</div>
				<p class="errores"><?php echo isset($_COOKIE["errores"]) ? $_COOKIE["errores"] : ""?></p>
	
	</body>
</html>