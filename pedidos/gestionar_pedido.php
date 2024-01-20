<?php
	session_start();
	if(!isset($_SESSION["usuario"])){
		header("Location:index.php");
	};
        /////declaracion de variables y lectura de datos//////////
        $host='localhost';
        $user='root';
        $pass='';
       
		$stm = "";
        ///////////////////conexion a base de datos///////////////////////////

	try{

		$pdo = new PDO("mysql:host=$host;dbname=pedidos;charset=utf8",$user,$pass);
		$sentencia="SELECT * FROM pedidos";
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
									<h2>Gestionar Pedido</h2>
                                    
								</header>

							<!-- Banner -->
								<section id="formulario">
                                <p>Seleccione un pedido para actualizar su estado</p>
                                <div class="content">
                                        <!--EL CODIGO COMIENZA AQUI-->
                                        <table>
                                            <tr>
                                                <td>ID</td>
                                                <td>Restaurante</td>
												<td>Estado</td>
                                                <td>Estado</td>
                                                <td>Acciones</td>
                                            </tr>
                                            <?php
                                    
                                        while ($fila=$stm->fetch()){

											$sql2 = "SELECT correo from restaurantes where codres = :codres";
											$stm2 = $pdo->prepare($sql2);
											$stm2->bindParam(":codres", $fila[3]);
											$stm2->execute();
											$stm2->setFetchMode(PDO::FETCH_BOTH);
											/**
											 * Obtiene la siguiente fila del resultado de la consulta y la asigna a la variable $fila2.
											 */
											while ($fila2=$stm2->fetch()){
												$restaurante = $fila2[0];

												if($fila[2] == 0){
													$imprimir = "Sin Enviar";
													echo '<tr>';
													echo '<td>'.$fila[0].'</td>';
													echo '<td>'.$restaurante.'</td>';
													echo '<td>'.$fila[1].'</td>';
													echo '<td>'.$imprimir.'</td>';
													echo '<td><a class="button small" href="procesar_gestionar_pedido.php?id='.$fila[0].'&fecha='.$fila[1].'">Actualizar</a></td>';
												}else{
													$imprimir = "Enviado";
													echo '<tr>';
													echo '<td>'.$fila[0].'</td>';
													echo '<td>'.$restaurante.'</td>';
													echo '<td>'.$fila[1].'</td>';
													echo '<td>'.$imprimir.'</td>';
													echo '<td></td>';
												}
											}

                                            

                                        };

                                        ?>
                                        </table>

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