<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location:index.php");
};

////////////////////VARIABLES PARA RESTAURANTE////////////////////////
$codres = "";
$correo = "";
$clave = "";
$confirmaClave = "";
$pais = "";
$cp = "";
$ciudad = "";
$direccion = "";

/***Esta clase parece estar terminada no tocar */

/////declaracion de variables y lectura de datos//////////
$salida = "";
$hay_errores = "";
$errores = false;

/////////////////////COMPROBAR SI VIENE DE POST///////////////////////////
function limpiarDato($dato)
{
    return trim(htmlspecialchars($dato));
};

if (isset($_GET['codres'])) {
    $codres = $_GET['codres'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //comprobar que los campos no estén vacíos
    $correo = isset($_POST["correo"]) ? limpiarDato($_POST["correo"]) : "";
    $clave = isset($_POST["clave"]) ? limpiarDato($_POST["clave"]) : "";
    $confirmaClave = isset($_POST["confirmaClave"]) ? limpiarDato($_POST["confirmaClave"]) : "";
    $pais = isset($_POST["pais"]) ? limpiarDato($_POST["pais"]) : "";
    $cp = isset($_POST["cp"]) ? limpiarDato($_POST["cp"]) : "";
    $ciudad = isset($_POST["ciudad"]) ? limpiarDato($_POST["ciudad"]) : "";
    $direccion = isset($_POST["direccion"]) ? limpiarDato($_POST["direccion"]) : "";

    if ($correo == "") {
        $hay_errores .= "El campo correo esta vacio <br>";
        $errores = true;
    }
    if ($clave == "") {
        $hay_errores .= "El campo clave esta vacio <br>";
        $errores = true;
    }
    if ($confirmaClave == "") {
        $hay_errores .= "El campo confirmaClave esta vacio <br>";
        $errores = true;
    }
    if ($pais == "") {
        $hay_errores .= "El campo pais esta vacio <br>";
        $errores = true;
    }
    if ($cp == "") {
        $hay_errores .= "El campo cp esta vacio <br>";
        $errores = true;
    }
    if ($ciudad == "") {
        $hay_errores .= "El campo ciudad esta vacio <br>";
        $errores = true;
    }
    if ($direccion == "") {
        $hay_errores .= "El campo direccion esta vacio <br>";
        $errores = true;
    }
    if ($clave !== $confirmaClave) {
        $hay_errores .= "La contraseña y la confirmacion son distintas<br>";
        $errores = true;
    };

    //actualizar los datos en la tabla
    if ($errores == false) {

        try {
            include '../includes/conexion_bd.php';

            // Modificación en la sentencia UPDATE
            if ($clave !== $confirmaClave) {
                $sentencia = "UPDATE restaurantes SET correo = :correo, clave = :clave, pais = :pais, cp = :cp, ciudad = :ciudad, direccion = :direccion WHERE correo = :correo";
            } else {
                $sentencia = "UPDATE restaurantes SET correo = :correo, pais = :pais, cp = :cp, ciudad = :ciudad, direccion = :direccion WHERE correo = :correo";
            }

            $stm = $pdo->prepare($sentencia);

            $stm->bindParam(':correo', $correo);
            $stm->bindParam(':pais', $pais);
            $stm->bindParam(':cp', $cp);
            $stm->bindParam(':ciudad', $ciudad);
            $stm->bindParam(':direccion', $direccion);

            // Asegúrate de asignar la contraseña solo si ha cambiado
            if ($clave !== $confirmaClave) {
                $stm->bindParam(':clave', $clave);
            }

            $stm->execute();
            $stm->setFetchMode(PDO::FETCH_ASSOC);

            if ($stm->rowCount() > 0) {
                $salida = "Se han actualizado los datos correctamente";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        };
    }
} else {
    ///////////////////conexion a base de datos///////////////////////////
    try {
        include '../includes/conexion_bd.php';
        $sentencia = "SELECT codres, correo, clave, pais, cp, ciudad, direccion FROM restaurantes WHERE codres = :codres";
        $stm = $pdo->prepare($sentencia);
        $stm->bindParam(':codres', $codres);

        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_BOTH);

        if ($stm->rowCount() == 0) {
            $hay_errores = "No se ha podido encontrar el restaurante en la BD";
        } else {
            foreach ($stm as $fila) {
                $codres = $fila['codres'];
                $correo = $fila['correo'];
                $clave = $fila['clave'];
                $confirmaClave = $fila['clave'];
                $pais = $fila['pais'];
                $cp = $fila['cp'];
                $ciudad = $fila['ciudad'];
                $direccion = $fila['direccion'];
            }
        }
    } catch (PDOException $e) {
        echo $hay_errores + "<br>";
        echo $e->getMessage();
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Restaurante</title>
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
									<h2>Modificar Restaurante</h2>
    
                                    
								</header>

							<!-- Banner -->
								<section id="formulario">
                                <div class="content">
                                        <!--AQUI EMPIEZA EL FORMULARIO-->
                                        <form action="modificar_restaurante.php" method="POST">
                                        <h2>Actualizar datos del Restaurante</h2>
                                            <div class="row gtr-uniform">
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="correo">Correo</label>
                                                    <input type="text" name="correo" id="correo" value="<?php echo $correo; ?>" readonly >
                                                    
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="clave">Clave</label>
                                                    <input type="password" name="clave" id="clave" value="<?php echo $clave; ?>">
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="confirmaClave">Confirma Clave</label>
                                                    <input type="password" name="confirmaClave" id="confirmaClave" value="<?php echo $confirmaClave; ?>">
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="pais">País</label>
                                                    <input type="text" name="pais" id="pais" value="<?php echo $pais; ?>">
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="cp">Código Postal</label>
                                                    <input type="text" name="cp" id="cp" value="<?php echo $cp; ?>">
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="ciudad">Ciudad</label>
                                                    <input type="text" name="ciudad" id="ciudad" value="<?php echo $ciudad; ?>">
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="direccion">Dirección</label>
                                                    <input type="text" name="direccion" id="direccion" value="<?php echo $direccion; ?>">
                                                </div>
                                                
                                                <!-- Break -->
                                                <div class="col-12">
                                                    <ul class="actions">
                                                        <li><input type="submit" value="Actualizar Datos" class="primary"/></li>
                                                        <li>
                                                            <?php
                                                                echo $salida;
                                                                echo isset($hay_errores) ? $hay_errores : "";
                                                            ?>
                                                        </li>
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
</body>
</html>