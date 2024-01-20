<?php
session_start();
if(!isset($_SESSION["usuario"])){
	header("Location:index.php");
};
try{

	include "../includes/conexion_bd.php";


	$stm_categorias=$pdo->prepare("SELECT * from categorias");
	$stm_categorias->execute();
	$categorias = $stm_categorias->fetchAll();
    $pdo = null;
	

}catch(PDOException $a){


	$errores=$errores.$a;
	echo $errores;

   
};

if($_SERVER["REQUEST_METHOD"]=="POST"){
	
 /////declaracion de variables y lectura de datos//////////
 $host='localhost';
 $user='root';
 $pass='';
 $id='';
 ///////////////////conexion a base de datos///////////////////////////

try{

 $pdo = new PDO("mysql:host=$host;dbname=pedidos;charset=utf8",$user,$pass);


 

    $id=isset($_POST["categoria"]) ? htmlspecialchars(trim($_POST["categoria"])) : "";

 
    $sentencia="SELECT * FROM productos WHERE categoria= :id";
    $stm=$pdo->prepare($sentencia);
    $stm->bindParam(':id',$id);

    $stm->execute();
    $stm->setFetchMode(PDO::FETCH_BOTH);
 

}catch(PDOException $e){

 echo $e->getMessage();
};
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
									<h2>Productos por categoria.</h2>
								</header>

							<!-- Banner -->
								<section id="productos_por_categoria">
									<form action="listar_productos_categoria.php" method="POST">
										<table>
											<tr>
												<td><label for="categoria">Selecciona una categoria</label></td>
												<td><select name="categoria">
												<?php
													foreach ($categorias as $categoria) {
														echo '<option value="' . $categoria[0] . '">' . $categoria[1] . ' , ' . $categoria[2] . '</option>';
													};
												?>
												</select></td>
												<td><input type="submit"></td>
											</tr>
										</table>
                                    </form>
									<table>
                                            <tr>
                                                <td>CodProd</td>
                                                <td>Nombre</td>
                                                <td>Descripcion</td>
                                                <td>Peso</td>
                                                <td>Stock</td>
                                                <td>Categroria</td>
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
                                            echo '</tr>';
                                        };
									};
                                        ?>
                                        </table>
								</section>
						</div>
					</div>

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
							<!-- Menu -->
								<nav id="menu">
									<!--La conexion no esta suplicada esto inluye un menu en funcion del tipo de usuario(restaurante o administrador)-->
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