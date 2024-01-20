<?php
session_start();
if(!isset($_SESSION["usuario"])){
	header("Location:index.php");
};

if($_SERVER["REQUEST_METHOD"]=="POST"){
	
	$valido=true;
	$errores='Los errores encontrados son: ';

	/////////////lectura de datos////

	function limpiaDato($dato){
		return trim(htmlspecialchars(strip_tags($dato)));
	};

$criterio=isset($_POST["criterio"]) ? limpiaDato($_POST["criterio"])	: "";

$valor=isset($_POST["valor"]) ? limpiaDato($_POST["valor"])	: "";

//echo 'los valores de las variables son '.$criterio.' y '.$valor;


        if(empty($criterio)){
            $errores=$errores.'<br>-No has inidcado ningun criterio';
            $valido=false;
        };
        if(empty($valor)){
            $errores=$errores.'<br>-No has inidcado ningun valor';
            $valido=false;
        };


        if($valido===true){


            try{

                include "../includes/conexion_bd.php";

            
                $stm=$pdo->prepare("SELECT codprod,nombre,descripcion,peso,stock,categoria,ruta from productos where $criterio like :valor");

                //echo 'acabo derealizar la conexion';
				$valor='%'.$valor.'%';
                $stm->bindParam(':valor', $valor);

                //echo 'acabo de insertar '.$criterio.' y '.$valor.'En el prepared';


                $stm->execute();

                //echo 'Sentencia ejecutada';

                $pdo=null;

            }catch(PDOException $e){


                $errores=$errores.$e;
                echo $errores;

               
            };

        }

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
									<h2>Buscar producto</h2>
								</header>

							<!-- Banner -->
								<section id="formulario">
									<div class="content">
                                    <form action="find_producto.php" method="post">
											<table>
                                            <tr>
						
													<td><label for="criterio">Buscar por: </label></td>
													<td><select name="criterio" id="criterio">
													<option value="codprod">Codigo de Producto</option>
                                                    <option value="nombre">Nombre</option>
                                                    <option value="descripcion">Descripcion</option>
                                                    <option value="peso">Peso</option>
                                                    <option value="stock">Stock</option>
                                                    <option value="categoria">Categoria</option>
													</select>
													</td>
												</tr>
                                                <tr>
                                                    <td><label for="dato">Introduce el dato</label></td>
                                                    <td><input type="text" name="valor"></td>
                                                </tr>
                                                <tr>
                                                    <td><input type="submit"></td>
                                                </tr>
										</form>
                                        <table>
                                            <tr>
                                                <td>CodProd</td>
                                                <td>Nombre</td>
                                                <td>Descripcion</td>
                                                <td>Peso</td>
                                                <td>Stock</td>
                                                <td>Categoria</td>
												<td>Imagen</td>
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
													echo '<td><img class="rutas" src="..//imagenes_productos/'.$fila[6].'"></td>';
                                                    echo '</tr>';
                                                };
                                            };
                                        ?>
                                        </table>
										<p><?php echo isset($valido) && $valido===false ? $errores : "" ?></p>
										<p><?php echo isset($exito) ? $exito : ""  ?></p>
                                        <p><?php echo isset($e) ? $errores : ""  ?></p>

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