<?php
session_start();



if(!isset($_SESSION["usuario"])){
	header("Location:index.php");
};

$idPedido=isset($_COOKIE["pedido_en_curso"]) ? $_COOKIE["pedido_en_curso"] : 0;
/**Este archivo queda pendiente corregir el bind param de restaurante para que lo coja de la sesion */

//si no esta la cookie generamos un pedido nuevo, si está es que hay un pedido en proceso
if(!isset($_COOKIE["pedido_en_curso"])){

	try{
		include "../includes/conexion_bd.php";


		$stm=$pdo->prepare("INSERT into pedidos (fecha, enviado,restaurante) values (:fecha, :enviado, :restaurante )");
		$fecha= date("Y-m-d H:i:s", time());
		$enviado=0;
		//$restaurante=1;

		$stm->bindParam(':fecha',$fecha);
		$stm->bindParam(':restaurante',$_SESSION["codres"]);
		$stm->bindParam(':enviado',$enviado);

		$stm->execute();

		$idPedido=$pdo->lastInsertId();
		$pdo=null;

		setcookie("pedido_en_curso",$idPedido,time()+3600,"/");

	}catch(PDOException $e){
		echo $e->getMessage();
	};
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

            
                $stm=$pdo->prepare("SELECT codprod,nombre,descripcion,peso,stock,categoria from productos where $criterio like :valor ");

                //echo 'acabo derealizar la conexion';

                $stm->bindParam(':valor', $valor);

                //echo 'acabo de insertar '.$criterio.' y '.$valor.'En el prepared';


                $stm->execute();

                //echo 'Sentencia ejecutada';

                $pdo=null;

            }catch(PDOException $e){


                $errores=$errores.$e;
                echo $errores;

               
            };

        };

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
									<h2>Pedido <?php echo $idPedido ?>.Seleccion de productos.</h2>
								</header>

							<!-- Banner -->
								<section id="formulario">
									<div class="content">
                                    <form action="nuevo_pedido.php" method="post">
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
                                                    <td><input type="submit" value="buscar Producto"></td>
                                                </tr>
										</form>
										<form  action="../productos/añadirproductos.php" method="post">
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
                                                    echo '<td><input type="number" name="cantidad"></td>';
													echo '<input type="hidden" name="codped" value="'.$idPedido.'" >';
													echo '<td><input type="submit" name="anadir" value="anadir producto"></button></td>';
                                                    echo '</tr>';
                                                };
                                            };
                                        ?>
                                        </table>
										</form>
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