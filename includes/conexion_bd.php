<?php
    $cadena_conexion = 'mysql:dbname=pedidos;host=127.0.0.1';
    $usuario = 'root';
    $clave = '';
    $pdo = new PDO($cadena_conexion, $usuario, $clave);
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
?>