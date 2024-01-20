
<?php
///control de sesiones////

	session_start();
	/// control del flujo, 
	/*si llego aqui por get es o porque he ido a un archivo sin autorizacionç
	o bien porque algo no ha ido bien en la validacion o porque han ppulsado en cerrar sesion
	*/
	if($_SERVER["REQUEST_METHOD"]=="GET"){

		setcookie("errores", "", time()+3600);

		//// si se ha pulsado cerrar sesion, la sesion se destruye//
		if(isset($_SESSION["usuario"])){

			session_unset();
        
      
			session_destroy();

			$sesion="Sesion cerrada correctamente";
		};
		
	};
?>
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
									<a href="index.html" class="logo"><h2>Gestor de pedidos</h2> </a>
									<p class="errores">
										<?php echo isset($_COOKIE["errores"]) ? $_COOKIE["errores"] : "";?>
									</p>
								</header>

							<!-- Banner -->
								<section id="banner">
									<div class="content">
										<header>
											<h1>Inicio</h1>
											<p>Login general.</p>
										</header>
										<table>
											<form action="valida_formulario.php" method="POST">
												<tr>
													<td><label for="perfil">TIPO DE PERFIL</label></td>
													<td><select name="perfil" id="perfil">
														<option value="restaurante">restaurante</option>
														<option value="administrador">administrador</option>
													</select></td>
												</tr>
												<tr>
													<td><label for="usuario">USUARIO</label></td>
													<td><input name="usuario" id="usuario" type="text"></td>
												</tr>
												<tr>
													<td><label for="pass">CONTRASEÑA</label></td>
													<td><input name="pass" id="pass" type="password"></td>
												</tr>
												<tr>
													<td><input name="enviar" value="enviar" type="submit"></td>
													
												</tr>
													

											</form>
										</table>
									</div>
									
								</section>
						</div>
					</div>
				</div>
				
				<p>
					<?php 
					
						echo isset($sesion) ? $sesion : "";
					
					?>
				</p>
	
	</body>
</html>