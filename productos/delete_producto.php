<?php
session_start();
if(!isset($_SESSION["usuario"])){
	header("Location:index.php");
};
    
    if($_SERVER['REQUEST_METHOD']=="POST"){

        //echo 'trato el formulario';

        $valido=true;
        $errores='Los errores son:';
        $exito='';

        $codprod=isset($_REQUEST["codprod"]) ? $_REQUEST["codprod"] : "";

        if(empty($codprod)){

            $errores=$errores.'<br>-No has inidcado ningun producto';
            $valido=false;

        };
        
        if($valido==true){

            //echo 'comienzo a borrar';
            try{

                include "../includes/conexion_bd.php";

                $stm=$pdo->prepare("DELETE from productos where codprod = :codprod");
                $stm->bindParam(':codprod', $codprod);

                //echo 'acabo de insertar '.$codprod.'En el prepared';

                $stm->execute();

                //echo 'Sentencia ejecutada';

                $exito=$exito.'Se han borrado '.$stm->rowCount().' filas';

                $pdo=null;

            }catch(PDOException $e){

                $errores=$errores.'No puedes borrar ese producto porque ya tiene asociado un pedido';
               
            };

        };
    };
    
?>



<?php
    try{

        include "../includes/conexion_bd.php";
        $stm=$pdo->prepare("SELECT codprod,nombre from productos");
        $stm->execute();

        $pdo=null;

    }catch(PDOException $e){

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
									<h2>Eliminar producto</h2>
								</header>

							<!-- Banner -->
								<section id="formulario">
									<div class="content">
										<form action="delete_producto.php" method="post">
											<table>
												<tr>
													<td><label for="codprod">Seleccione Producto</label></td>
													<td><select name="codprod" id="categoria">
													<?php
														while($row = $stm->fetch()){
															echo '<option value="'.$row[0].'">'.$row[0].' '.$row[1].'</option>';
														};
													?>
													</select>
													</td>
												</tr>
                                                <tr>
													<td><input type="submit" name="addcategory" value="Eliminar"></td>

												</tr>
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