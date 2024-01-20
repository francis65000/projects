<?php
session_start();
if(!isset($_SESSION["usuario"])){
	header("Location:index.php");
};
	if(isset($_POST["anadir"])){
	//voy por aqui esta pendiente, lo he dejado que si el pedido esta en curso..... y si no....
		try{
			include "../includes/conexion_bd.php";

			$stm=$pdo->prepare("INSERT into pedidosproductos (pedido, producto, unidades) values (:pedido , :producto, :unidades)");

			$producto=$_POST["codprod"];
			$unidades=$_POST["cantidad"];
			$pedido=$_COOKIE["pedido_en_curso"];

			$stm->bindParam(':pedido',$pedido);
			$stm->bindParam(':producto',$producto);
			$stm->bindParam(':unidades',$unidades);

			$stm->execute();

			$codpedprod=$pdo->lastInsertId();

			setcookie("codpedprod",$codpedprod, time()+3600,"/");
			$pdo=null;

		}catch(PDOException $e){

		};
	};

	try{
		include "../includes/conexion_bd.php";
		$stm = $pdo->prepare("SELECT p.codprod, p.nombre, p.descripcion, p.peso, p.stock, p.categoria, pp.codpedprod
		FROM productos p
		JOIN pedidosproductos pp ON p.codprod = pp.producto
		WHERE pedido = :pedido");

		$stm->bindParam(":pedido", $_COOKIE["pedido_en_curso"]);
		$stm->execute();

		$pdo=null;

	}catch(PDOException $e){
		echo $e->getMessage();
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
									<h2>Productos añadidos.</h2>
								</header>

							<!-- Banner -->
								<section id="formulario">
									<div class="content">
										<form action="../pedidos/acciones.php" method="post">
                                        <table>
                                            <tr>
                                                <td>CodProd</td>
                                                <td>Nombre</td>
                                                <td>Descripcion</td>
                                                <td>Peso</td>
                                                <td>Stock</td>
                                                <td>Cantidad</td>
                                            </tr>
                                            <?php
											
                                            if(isset($stm)){
                                                while ($fila=$stm->fetch()){
                                                    echo '<tr>';
                                                    echo '<td><input type="text" name="codprod" value="'.$fila[0].'" readonly></td>';
                                                    echo '<td>'.$fila[1].'</td>';
                                                    echo '<td>'.$fila[2].'</td>';
                                                    echo '<td>'.$fila[3].'</td>';
                                                    echo '<td>'.$fila[4].'</td>';
													echo '<td>'.$fila[5].'</td>';
													echo '<input type="hidden" name="codpedprod" value="'.$fila[6].'">';
													echo '<td><input type="submit" name="eliminar" value="eliminar producto"></td></tr>';
                                                    echo '</tr>';
                                                };
                                            };
													echo '<td><input type="submit" name="finalizar" value="finalizar pedido"></td>';
													echo '<td><input type="submit" name="seguir" value="seguir comprando"</td>';
                                        ?>
                                        </table>
										</form>
									

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