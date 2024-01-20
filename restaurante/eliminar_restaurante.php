<?php
session_start();
if(!isset($_SESSION["usuario"])){
	header("Location:index.php");
};

if($_SERVER["REQUEST_METHOD"]=="POST"){

/////////////////////gestion de eliminacion//////////////////

    if(isset($_POST["enviar"])){
        
   
        
        $valido=true;
        $errores='Los errores encontrados son: ';
        $eliminado="";
        

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

            
                $stm=$pdo->prepare("SELECT codres,correo,clave,pais,cp,ciudad,direccion,rol from restaurantes where $criterio like :valor ");

                //echo 'acabo derealizar la conexion';

                $stm->bindParam(':valor', $valor);

                //echo 'acabo de insertar '.$criterio.' y '.$valor.'En el prepared';


                $stm->execute();

                //echo 'Sentencia ejecutada';

                $pdo=null;

            }catch(PDOException $a){


                $errores=$errores.$a;
                echo $errores;

               
            };

        };


}elseif(isset($_POST["eliminar"])){

    
    try{

        

        include "../includes/conexion_bd.php";

        $stm=$pdo->prepare("DELETE from restaurantes where correo like :correo");
        $stm->bindParam(":correo",$_POST["correo"]);

        

        $stm->execute();

        

        if($stm->rowCount()>0){
            $eliminado='Se ha realizado la eliminacion del retaurante '.$_POST["correo"].'. Se han afectado: '.$stm->rowCount().' filas';
        }else{
            $eliminado='ERROR: Restaurante no eliminado ';
        };

    }catch(PDOException $e){
        
        $eliminado='ERROR: El restaurante'.$_POST["correo"].' tiene pedidos asociados, y no se puede elimiar';
        
    };
    $pdo=null;
    
}else{
    echo 'error inesperado';
};
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editorial by HTML5 UP</title>
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
                    <h2>Eliminar restaurante</h2>
                </header>

                <!-- Banner -->

                <!-- Formulario de búsqueda -->
                <section id="formulario-busqueda">
                    <div class="content">
                        <form action="eliminar_restaurante.php" method="post">
                            <table>
                                <tr>
                                    <td><label for="criterio">Buscar por: </label></td>
                                    <td>
                                        <select name="criterio" id="criterio">
                                            <option value="codres">Codigo de Restaurante</option>
                                            <option value="correo">Correo</option>
                                            <option value="clave">Clave</option>
                                            <option value="pais">Pais</option>
                                            <option value="cp">Codigo Postal</option>
                                            <option value="ciudad">Ciudad</option>
                                            <option value="direccion">Direccion</option>
                                            <option value="rol">Rol</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="dato">Introduce el dato</label></td>
                                    <td><input type="text" name="valor"></td>
                                </tr>
                                <tr>
                                    <td><input type="submit" name="enviar" value="Eliminar"></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </section>

                <!-- Formulario de eliminación -->
                <section id="formulario-eliminacion">
                    <form action="eliminar_restaurante.php" method="post">
                        <table>
                            <tr>
                                <td>CodRes</td>
                                <td>Correo</td>
                                <td>Clave</td>
                                <td>Pais</td>
                                <td>Codigo Postal</td>
                                <td>Ciudad</td>
                                <td>Direccion</td>
                                <td>Rol</td>
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
                                    echo '<td>'.$fila[7].'</td>';
                                    echo '<td><input type="hidden" name="correo" value="' . $fila[1] . '"></td>';
                                    echo '<td><input type="submit" name="eliminar" value="Eliminar"></td>';
                                    echo '</tr>';
                                };
                            };
                            ?>
                        </table>
                    </form>
                </section>

                <p><?php echo isset($valido) && $valido===false && $errores!=""? $errores : "" ?></p>
                <p><?php echo isset($exito) ? $exito : ""  ?></p>
                <p><?php echo isset($e) ? $eliminado: ""  ?></p>
                <p><?php echo isset($eliminado) ? $eliminado : ""  ?></p>
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
                    } elseif($_SESSION["perfil"]=="administrador"){
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