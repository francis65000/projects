<?php

session_start();
	if(!isset($_SESSION["usuario"])){
		header("Location:index.php");
	};
    include_once "../includes/conexion_bd.php";
    
    //variables del control de errores
    $hay_errores = "";
    $errores = false;
    $salida = "";

    //si viene de post enlazamos conexión con BD
    
		//si el campo no esta vacio pasa al siguiente paso para eliminar la categoria
     
            try{
				
				//comprobamos que la categoría existe
                $stm = $pdo->prepare("SELECT p.codprod, p.nombre, p.descripcion, p.peso, p.stock, p.categoria, c.codcat, c.nombre as nombrecate 
                     FROM productos p
                     INNER JOIN categorias c ON p.categoria = c.codcat
                     GROUP BY p.codprod, p.nombre, p.descripcion, p.peso, p.stock, p.categoria, c.codcat, c.nombre");

                $stm->execute();

				$resultados = $stm->fetchAll(PDO::FETCH_ASSOC);
				
				

			}catch(PDOException $e){
                echo $e->getMessage();
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
											<h1>Editar Producto</h1>
											<p>Gestión integral de pedidos, grupo de restaurantes.</p>
										</header>
										<p>Introduzca la ID del producto que desea modificar</p>
										
                                        <!--FIN DEL FORMNULARIO-->
                                        <?php
                                            //salida info
                                            if($errores == true){
                                                echo("Se han producido los siguientes errores: <br> $hay_errores");
                                            }else{
                                                echo($salida);
                                            }
                                        ?>
                                        <!--AQUI EMPIEZA EL FORMULARIO-->
                                        <form action="procesar_editar_producto.php" method="POST">
                                        <h2>Actualizar datos de un Producto</h2>
                                            <div class="row gtr-uniform">
                                                <div class="col-6 col-12-xsmall"> 
                                                    <label for="codigo">Seleccione categoria</label>
                                                    <select name="codigo">
                                                        <?php
                                                            foreach ($resultados as $fila) {
                                                                echo '<option id="actualizar" value="' . $fila["codprod"] . '">' . $fila["nombre"] . '</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="nombre">Nuevo Nombre</label>
                                                    <input type="text" name="nombre" id="actualizar"/>
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="descripcion">Nueva Descripcion</label>
                                                    <input type="text" name="descripcion" id="actualizar"/>
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="peso">Nuevo Peso</label>
                                                    <input type="number" name="peso" id="actualizar"/>
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="stock">Nuevo Stock</label>
                                                    <input type="number" name="stock" id="actualizar"/>
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="categoria">Nueva Categoría</label>
                                                    <select name="codigo">
                                                        <?php
                                                            foreach ($resultados as $fila) {
                                                                echo '<option id="actualizar" value="' . $fila["codcat"] . '">' . $fila["nombrecate"] . '</option>';
                                                            }
                                                        ?>
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