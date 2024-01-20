<?php
session_start();

if(!isset($_SESSION["usuario"])){
    header("Location:index.php");
};
    include_once  "../includes//conexion_bd.php";
    //variables del control de errores
    $hay_errores = "";
    $errores = false;
    $salida = "";

    //variable que almacenara los datos de la ID
    $id_categoria = "";

    //variables del formulario
    $codigo = "";
    $nombre ="";
    $descripcion ="";


    //si viene de post enlazamos conexión con BD
    if($_SERVER["REQUEST_METHOD"]=="POST"){

        //recogemos datos del formulario - comprobando primerp que el campo no esté vacío
        if(!empty($_POST["categoria"])){
            $id_categoria = $_POST["categoria"];
        }else{
            $hay_errores = "El campo ID está vacío";
            $errores = true;
        }
		//si el campo no esta vacio pasa al siguiente paso para eliminar la categoria
        if($hay_errores==false){
            try{
			

				//comprobamos que la categoría existe
				$sql = "SELECT codcat, nombre, descripcion FROM categorias WHERE codcat = $id_categoria";
				$resultado = $pdo->query($sql);
				if($resultado->rowCount()==0){
					$hay_errores = "La categoría no existe";
					$errores = true;
				}else{
                    foreach ($resultado as $fila) {
                        //echo "ID: " . $fila['codcat'] . "<br>";
                        $codigo = $fila['codcat'];
                        //echo "Nombre: " . $fila['nombre'] . "<br>";
                        $nombre = $fila['nombre'];
                        //echo "Descripción: " . $fila['descripcion'] . "<br>";
                        $descripcion = $fila['descripcion'];
                    }
				}


			}catch(PDOException $e){
				echo "Se ha producido un error: <br> Error al conectar con la BD";
			};
        };
    };

    $stm=$pdo->prepare("select codcat, nombre from categorias");
    $stm->execute();


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
                                        <form action="procesar_editar_categoria.php" method="POST">
                                        <h2>Actualizar datos de Categoría</h2>
                                            <div class="row gtr-uniform">
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="codigo">Seleccione categoria</label>
                                                    <select name="codigo">
                                                        <?php
                                                            while($fila = $stm->fetch()){
                                                                echo '<option id="actualizar" value="'.$fila["codcat"].'">'.$fila["nombre"].'</option>';
                                                            };
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="nombre">Nuevo Nombre</label>
                                                    <input type="text" name="nombre" id="nombre"/>
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="descripcion">Nueva Descripcion</label>
                                                    <input type="text" name="descripcion" id="actualizar" value="<?php echo $descripcion; ?>"/>
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