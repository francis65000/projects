<?php   

if ($_SERVER["REQUEST_METHOD"] === "POST"){


    ///////función/////////
    function limpiarDato($dato){

        return trim(htmlspecialchars($dato));
    };

    ///////////////lectura de datos limpios///////////////////
    $errores = 'ERROr::';
    $valido = true;

    $perfil = isset($_POST["perfil"]) ? limpiarDato($_POST["perfil"]) : "";
    $user = isset($_POST["usuario"]) ? limpiarDato($_POST["usuario"]) : "";
    $pass = isset($_POST["pass"]) ? limpiarDato($_POST["pass"]) : "";
    $respuesta='';
    $id='';
    ///////////////////verificacion de requisitos//////////////////////
    if($perfil == ""){

        $errores = $errores.'-No se puede dejar vacío el tipo de perfil.   ';
        $valido = false;
    };
    if($user == ""){

        $errores = $errores.'-No se puede dejar vacío el usuario';
        $valido = false;
    };
    if($pass == ""){

        $errores = $errores.'-No se puede dejar vacía la contraseña ';
        $valido = false;
    };
    //////////// si analizados los datos, alguno no es valido(vacio), ya no se sigue gestionando, devuelve al inicio//////
    if(!$valido){
        setcookie("errores", $errores, time()+3600);
        header("Location:index.php");
        exit;
    };
    /**si los datos estan bien, comprobamos que exista el usuario y que la contraseña sea correcta */


    /////////verificacion claves de restaurante////////////
    if ($perfil == "restaurante") {

        try {
            include '../includes/conexion_bd.php';

            $sql = 'SELECT codres, clave FROM restaurantes WHERE correo = :usuario ';

            $stm = $pdo->prepare($sql);

            $stm->bindValue(":usuario", $user);

            $stm->execute();

            $pdo = null;

            if ($stm->rowCount() > 0) {

                $respuesta = $stm->fetch();

                $id = $respuesta[0];

                if ($pass != $respuesta[1]) {
                    $errores = $errores . '-La contraseña no es correcta <br>contraseña formulario= ' . $pass . ' contraseña de la base: ' . $respuesta[1];
                    $valido = false;
                };

            } else {
                $errores = $errores . '-El usuario no existe <br>   usuario  '.$user.'  perfil  '.$perfil.'  passs'.$pass;
                $valido = false;
            };

        } catch (PDOException $e) {
            echo $e->getMessage();
        };

    };

    ///////verificacion claves de administrador////////////

    if ($perfil == "administrador") {

        try {
            include '../includes/conexion_bd.php';

            $sql = 'SELECT id, pass FROM administradores WHERE usuario = :usuario ';

            $stm = $pdo->prepare($sql);

            $stm->bindValue(':usuario', $user);

            $stm->execute();
            $pdo = null;

            if ($stm->rowCount() > 0) {

                $respuesta = $stm->fetch();

                $id = $respuesta[0];

                if ($pass != $respuesta[1]) {
                    $errores = $errores . '-La contraseña no es correcta <br>contraseña formulario= ' . $pass . ' contraseña de la base: ' . $respuesta[1];
                    $valido = false;
                };

            } else {
                $errores = $errores . '-El usuario no existe <br>';
                $valido = false;
            };

        } catch (PDOException $e) {
            echo $e->getMessage();
        };

    };

    ////acciones a realizar en función de los resultados//////

    if($valido){
        ///////si todo es correcto le damos acceso a todos los documentos con la sesion y le redirigimos a su menú/////

        session_start();
        
        $_SESSION["usuario"] = $user; // Guardar el usuario en la sesión
        $_SESSION["perfil"]=$perfil;
        $_SESSION["codres"] = $id;
        /**Esto es unificable cuando se arregle el bind param */
        if($perfil == "restaurante"){
           
            header("Location:inicio_restaurante.php");

        } else if($perfil == "administrador"){
            
            header("Location:inicio_administrador.php");
        };

    } else {
        setcookie("errores", $errores, time()+3600);
        header("Location:index.php");
        exit;
    };
};
?>
