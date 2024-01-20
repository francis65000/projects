<?php
session_start();
if(!isset($_SESSION["usuario"])){
    header("Location:index.php");
};
    //variables del control de errores
    $hay_errores = "";
    $errores = false;
    $salida = "";

    //variables del formulario
    $codigo = "";
    $nombre ="";
    $descripcion ="";


    //si viene de post enlazamos conexión con BD
    if($_SERVER["REQUEST_METHOD"]=="POST"){

        //recogemos datos del formulario - comprobando primero que el campo no esté vacío
        if(!empty($_POST["codigo"])){
            $codigo = htmlspecialchars($_POST["codigo"]);
        }else{
            $hay_errores = "El campo ID está vacío";
            $errores = true;
        }
        //recogemos datos del formulario - comprobando primero que el campo no esté vacío
        if(!empty($_POST["nombre"])){
            $nombre = htmlspecialchars($_POST["nombre"]);
        }else{
            $hay_errores = "El campo nombre está vacío";
            $errores = true;
        }
        //recogemos datos del formulario - comprobando primero que el campo no esté vacío
        if(!empty($_POST["descripcion"])){
            $descripcion = htmlspecialchars($_POST["descripcion"]);
        }else{
            $hay_errores = "El campo descripcion está vacío";
            $errores = true;
        }

        //recogemos datos del formulario - comprobando primero que el campo no esté vacío
        if(!empty($_POST["codigo"])){
            $id_categoria = $_POST["codigo"];
        }else{
            $hay_errores = "El campo ID está vacío";
            $errores = true;
        }

        //si no hay errores, procedemos a actualizar la categoría
        if($hay_errores == false){
            try{
                //conexión bd
                $cadena_conexion = 'mysql:dbname=pedidos;host=127.0.0.1';
                $usuario = 'root';
                $clave = '';
                $bd = new PDO($cadena_conexion, $usuario, $clave);

                //comprobamos que la categoría existe
                $sql = "SELECT codcat, nombre, descripcion FROM categorias WHERE codcat = $id_categoria";
                $resultado = $bd->query($sql);
                if($resultado->rowCount()==0){
                    $hay_errores = "La categoría no existe";
                    $errores = true;
                }else{
                    //actualizamos los campos de la categoría
                    $actualizacion = "UPDATE categorias SET codcat='".$codigo."', nombre='".$nombre."', descripcion='".$descripcion."' WHERE codcat = $id_categoria";
                    $resultado = $bd->query($actualizacion);

                    //comprobación de errores
                    if($resultado){
                        $salida = "Actualización de datos de la categoria " . $nombre . " realizada correctamente.";
                    }else{
                        $hay_errores = "Se ha producido un error al actualizar los datos";
                        $errores = true;
                    }
                }

            }catch(PDOException $e){
                $hay_errores .= "-Se ha producido un error al actualizar los datos";
                $errores = true;
            }
        }
    }else{
        header("Location: editar_categoria.php");
    }
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
									<h2>Gestor de pedidos</h2>
								</header>

							<!-- Banner -->
								<section id="banner">
									<div class="content">
										<header>
											<h1>Editar Categoría</h1>
											<p>Gestión integral de pedidos, grupo de restaurantes.</p>
										</header>
                                        <?php
                                            //salida info
                                            if($errores == true){
                                                echo("Se han producido los siguientes errores: <br> $hay_errores");
                                            }else{
                                                echo($salida);
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