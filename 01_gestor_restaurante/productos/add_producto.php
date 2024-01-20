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

$nombre=isset($_POST["nombre"]) ? limpiaDato($_POST["nombre"])	: "";
$descripcion=isset($_POST["descripcion"]) ? limpiaDato($_POST["descripcion"])	: "";
$peso=isset($_POST["peso"]) ? limpiaDato($_POST["peso"])	: "";
$stock=isset($_POST["stock"]) ? limpiaDato($_POST["stock"])	: "";
$categoria=isset($_REQUEST["categoria"]) ? limpiaDato($_REQUEST["categoria"]) : "";
$imagen="";

$allowedFileTypes = ['image/jpeg', 'image/png', 'application/pdf']; // Tipos de archivos permitidos

if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

    $uploadedFileType = $_FILES['imagen']['type'];
	$nombreArchivo = $_FILES['imagen']['name'];

    if (in_array($uploadedFileType, $allowedFileTypes)) {
        // El tipo de archivo es válido, puedes procesar o mover el archivo.
        move_uploaded_file($_FILES['imagen']['tmp_name'], '../imagenes_productos/'.$nombreArchivo.'');
		
		$imagen=$nombreArchivo;
        echo 'Archivo subido exitosamente.';
    } else {
        echo 'Tipo de archivo no permitido.';
    }
} else {
    echo 'Error al subir el archivo.';
}



	///////////////requisitos de nombre /////////////////
if(empty($imagen)){
	$errores=$errores.'<br> - No se ha cargado ninguna imagen .';
	$valido=false;
};

if(empty($nombre)){
	$errores=$errores.'<br> - El nombre está vacio.';
	$valido=false;
};
if(strlen($nombre)>45){
	$errores=$errores.'<br> - La longitud del nombre no puede exceder de 45.';
	$valido=false;
};

	///////////////////reuiquisitos de descripcion/////////////////////

if(empty($descripcion)){
	$errores=$errores.'<br> - La descripcion está vacio.';
	$valido=false;
};
if(strlen($descripcion)>45){
	$errores=$errores.'<br> - La longitud de la descripcion no puede exceder de 90.';
	$valido=false;
};

	/////////////////////requisitos de peso/////////////////////////////////////

if(empty($peso)){
	$errores=$errores.'<br> - El peso está vacio.';
	$valido=false;
};

	////////////////////requisitos de stock//////////////////////////////////

if(empty($stock)){
	$errores=$errores.'<br> - El stock está vacio.';
	$valido=false;
};

	///////////////////requisitos de categoria////////////////////////////////
if(empty($categoria)){
	$errores=$errores.'<br> - La categoria está vacia.';
	$valido=false;
};

try{

	include "../includes/conexion_bd.php";
	$stm=$pdo->prepare("INSERT INTO productos (nombre, descripcion, peso,stock,categoria,ruta) values (:nombre, :descripcion, :peso, :stock, :categoria,:imagen)");
	$stm->bindParam(':nombre',$nombre);
	$stm->bindParam(':descripcion',$descripcion);
	$stm->bindParam(':peso',$peso);
	$stm->bindParam(':stock',$stock);
	$stm->bindParam(':categoria',$categoria);
	$stm->bindParam(':imagen',$imagen);

	$stm->execute();
	
	$pdo=null;

}catch(PDOException $e){
	$resultado=$e->getMessage();
};

if(!isset($resultado)){
	$resultado='El registro ha sido insertado correctamente';
}else{
	$resultado=$e->getMessage();
};

};
			//////////fin de la primera conexion de insercion/////////


			/////////inicio de consulta select para categoria//////////

try{

include "../includes/conexion_bd.php";
$stm=$pdo->prepare("SELECT codcat,nombre from categorias");
$stm->execute();

}catch(PDOExceocion $e){
	$errores=$errores.$e;
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
									<h2>Añadir Producto</h2>
								</header>

							<!-- Banner -->
								<section id="formulario">
									<div class="content">
										<form action="add_producto.php" method="post" enctype="multipart/form-data">
											<table>
												<tr>
													<td><label for="nombre">Nombre</label></td>
													<td><input type="text" name="nombre" id="nombre"></td>
												</tr>
												<tr>
													
													<td><label for="descripcion">Descripcion</label></td>
													<td><input type="text" name="descripcion" id="descripcion"></td>
												</tr>
												<tr>
													
													<td><label for="peso">Peso</label></td>
													<td><input class="input" type="float" name="peso" id="peso"></td>
												</tr>
												<tr>
													
													<td><label for="stock">Stock</label></td>
													<td><input class="input" type="number" name="stock" id="stock"></td>
												</tr>
												<tr>
													
													<td><label for="categoria">Categoria</label></td>
													<td><select name="categoria" id="categoria">
													<?php
														while($row = $stm->fetch()){
															echo '<option value="'.$row[0].'">'.$row[0].' '.$row[1].'</option>';
														};
													?>
													</select>
													</td>
												</tr>
												<tr>
													<td><label for="imagen">Subir Imagen</label></td>
													<td><input class="input" type="file" name="imagen" id="imagen"></td>
												</tr>
												<tr>
													<td><input type="submit" name="addcategory"></td>

												</tr>
											</table>

										</form>
										

										<p><?php echo isset($valido) && $valido===false ? $errores : "" ?></p>
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