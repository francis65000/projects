<?php

///control de accesos con sesion/////
session_start();
if(!isset($_SESSION["usuario"])){
	header("Location:index.php");
};

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $accion = isset($_POST["eliminar"]) ? $_POST["eliminar"] : (isset($_POST["finalizar"]) ? $_POST["finalizar"] : $_POST["seguir"]);
    $mensaje='';

    switch($accion){

        case "finalizar pedido":

			ob_start(); 
            setcookie("pedido_en_curso","",time()-3600,"/");

            $mensaje='Tu pedido se ha finalizado correctamente';
            break;

        case "seguir comprando":

            header("Location:nuevo_pedido.php");
            break;

        case "eliminar producto":

            try{
                include "../includes/conexion_bd.php";
        
                $stm = $pdo->prepare("DELETE FROM pedidosproductos WHERE codpedprod = :codpedprod AND producto = :producto");

        
                $codpedprod= $_POST["codpedprod"];
                $producto=$_POST["codprod"];
                
        
                $stm->bindParam(':codpedprod',$codpedprod);
                $stm->bindParam(':producto',$producto);
               
        
                $stm->execute();

                $mensaje='Tu producto ha sido eliminado';
        
                $pdo=null;

                header("Location:../productos/añadirproductos.php");
        
            }catch(PDOException $e){
                echo $e->getMessage();
            };
            break;
        default:
        
    }
}
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
									<h2>Pedido finalizado</h2>
								</header>

							<!-- Banner -->
								<section id="formulario">
									<div class="content">
										<form action="acciones.php" method="post">
                                        
                                        <p><?php echo isset($mensaje) ? $mensaje : ""  ?></p>

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