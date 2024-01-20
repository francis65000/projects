<?php
	session_start();
	if(!isset($_SESSION["usuario"])){
		header("Location:index.php");
	};
	
        /////declaracion de variables y lectura de datos//////////
        $host='localhost';
        $user='root';
        $pass='';
       
        ///////////////////conexion a base de datos///////////////////////////

	try{

		include_once "../includes/conexion_bd.php";
		
		$sentencia="SELECT * FROM categorias";
		$stm=$pdo->prepare($sentencia);
		
		$stm->execute();
        $stm->setFetchMode(PDO::FETCH_BOTH);

		//$resultado="El registro se ha insertado con exito,modificaciones: ".$stm->rowCount();

	}catch(PDOException $e){

		echo $e->getMessage();
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
									<h2>Categorias existentes</h2>
								</header>

							<!-- Banner -->
								<section id="lista">
									<div class="content">

                                        <!--EL CODIGO COMIENZA AQUI-->

                                        <table>
                                            <tr>
                                                <td>CodCat</td>
                                                <td>Nombre</td>
                                                <td>Descripcion</td>
                                            </tr>
                                            <?php
                                        
                                        while ($fila=$stm->fetch()){
                                            echo '<tr>';
                                            echo '<td>'.$fila[0].'</td>';
                                            echo '<td>'.$fila[1].'</td>';
                                            echo '<td>'.$fila[2].'</td>';
                                            echo '</tr>';
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