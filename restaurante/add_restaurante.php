<?php
session_start();
if(!isset($_SESSION["usuario"])){
    header("Location:index.php");
};
/***Esta clase parece estar terminada no tocar */


include '../includes/conexion_bd.php';
//////////////////**GESTION BACK DEL FORMULARIO *////////////////////
if($_SERVER["REQUEST_METHOD"]=="POST"){
    ////////////////////variablees globales/////////////////////////////
    $errores="";
    $resultado="";


    ////////////////////variablees globales/////////////////////////////
    $errores="";
    $resultado="";
    //////////////////**GESTION BACK DEL FORMULARIO *////////////////////


	///////////////////////funciones/////////////////////////

	function limpiaDatos($dato){
		return trim(htmlspecialchars(strip_tags($dato)));
	};

	/////declaracion de variables y lectura de datos//////////

	$correo=isset($_POST["correo"]) ? limpiaDatos($_POST["correo"]) : "";
    $clave=isset($_POST["clave"]) ? limpiaDatos($_POST["clave"]) : "";
    $confirmaClave=isset($_POST["confirmaClave"]) ? limpiaDatos($_POST["confirmaClave"]) : "";
    $pais=isset($_POST["pais"]) ? limpiaDatos($_POST["pais"]) : "";
    $cp=isset($_POST["cp"]) ? limpiaDatos($_POST["cp"]) : "";
    $ciudad=isset($_POST["ciudad"]) ? limpiaDatos($_POST["ciudad"]) : "";
    $direccion=isset($_POST["direccion"]) ? limpiaDatos($_POST["direccion"]) : "";


	
	$host='localhost';
	$user='root';
	$pass='';
	
	if($clave != $confirmaClave){
		$errores="Las contraseñas no coinciden.";

	}else{



	///////////////////conexion a base de datos///////////////////////////

	try{

        $sentencia = "INSERT INTO restaurantes (correo, clave, pais, cp, ciudad, direccion) VALUES (:correo, :clave, :pais, :cp, :ciudad, :direccion)";
        $stm = $pdo->prepare($sentencia);

        $stm->bindParam(":correo", $correo);
        $stm->bindParam(":clave", $clave);
        $stm->bindParam(":pais", $pais);
        $stm->bindParam(":cp", $cp);
        $stm->bindParam(":ciudad", $ciudad);
        $stm->bindParam(":direccion", $direccion);

        $stm->execute();

		$resultado="El registro del restaurante ".$correo." se ha insertado con exito";

		$stm=null;

	}catch(PDOException $e){

		echo $e->getMessage();
	};

};


};

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Añadir Restaurante</title>
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
                                        <h2>Gestión de pedidos web</h2>
                                    </header>

                                <!-- Banner -->
                                    <section id="formulario">
                                        <div class="content">
                                        <form action="add_restaurante.php" method="POST">
                                        <h2>Añadir nuevo Restaurante</h2>
                                            <div class="row gtr-uniform">
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="correo">Correo</label>
                                                    <input type="text" name="correo" id="actualizar" >
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="clave">Clave</label>
                                                    <input type="password" name="clave" id="actualizar" >
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="confirmaClave">Confirma Clave</label>
                                                    <input type="password" name="confirmaClave" id="actualizar">
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="pais">País</label>
                                                    <input type="text" name="pais" id="actualizar">
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="cp">Código Postal</label>
                                                    <input type="text" name="cp" id="actualizar">
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="ciudad">Ciudad</label>
                                                    <input type="text" name="ciudad" id="actualizar">
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    <label for="direccion">Dirección</label>
                                                    <input type="text" name="direccion" id="actualizar">
                                                </div>
                                                
                                                <!-- Break -->
                                                <div class="col-12">
                                                    <ul class="actions">
                                                        <li><input type="submit" value="Añadir" class="primary"/></li>
                                                        <li>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </form>
                                        <!--FIN DEL FORMNULARIO-->
                                            

                                            <p><?php echo isset($errores) ? $errores : "" ?></p>
                                            <p><?php echo isset($resultado) ? $resultado : ""  ?></p>

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