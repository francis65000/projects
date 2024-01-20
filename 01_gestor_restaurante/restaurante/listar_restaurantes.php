<?php

session_start();
if(!isset($_SESSION["usuario"])){
	header("Location:index.php");
};

include "../includes/conexion_bd.php";

            
$stm=$pdo->prepare("SELECT * from restaurantes");

$stm->execute();

$pdo=null;

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
                                        <h2>Listado de restaurantes</h2>
                                        <table>
                            <tr>
                                <td>CodRes</td>
                                <td>Correo</td>
                                <td>Clave</td>
                                <td>Pais</td>
                                <td>Codigo Postal</td>
                                <td>Ciudad</td>
                                <td>Direccion</td>
                                <td>Acciones</td>
                            </tr>
                            <?php
                            if(isset($stm)){
                                while ($fila=$stm->fetch()){
                                    echo '<tr>';
                                    echo '<td>'.$fila[0].'</td>';
                                    echo '<td>'.$fila[1].'</td>';
                                    echo '<td>'.$fila[2].'</td>';
                                    echo '<td>'.$fila[3].'</td>';
                                    echo '<td>'.$fila[4].'</td>';
                                    echo '<td>'.$fila[5].'</td>';
                                    echo '<td>'.$fila[6].'</td>';
                                    echo '<td><a class="button small" href="modificar_restaurante.php?codres='.$fila[0].'">Modificar</a></td>';
                                    echo '</tr>';
                                };
                            };
                            ?>
                        </table>
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